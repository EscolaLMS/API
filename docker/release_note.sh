#!/bin/bash

curl "https://hub.docker.com/v2/namespaces/escolalms/repositories/api/tags?page_size=100" --output tags.json

TAG_LATEST="$(docker run -i stedolan/jq <tags.json '.results[1].name' -r)"
TAG_PREVIOUS="$(docker run -i stedolan/jq <tags.json '.results[2].name' -r)"

docker run --rm --entrypoint cat escolalms/api:"$TAG_LATEST" /var/www/html/composer.lock > composer.latest.lock.json
docker run --rm --entrypoint cat escolalms/api:"$TAG_PREVIOUS" /var/www/html/composer.lock > composer.previous.lock.json

echo 'const fs = require("fs");

const packagesLatest = JSON.parse(
  fs.readFileSync("./composer.latest.lock.json")
);

const packagesPrevious = JSON.parse(
  fs.readFileSync("./composer.previous.lock.json")
);

const results = packagesLatest.packages
  .filter((pck) => pck.name.includes("escolalms/"))
  .map((pck) => {
    const prevPackage = packagesPrevious.packages.find(
      (el) => el.name === pck.name
    );

    if (prevPackage) {
      if (prevPackage.version !== pck.version) {
        return `| \`${pck.name}\` | [${pck.version}](https://github.com/${pck.name}/releases/tag/${pck.version}) | [${prevPackage.version}](https://github.com/${pck.name}/releases/tag/${prevPackage.version}) |`;
      }
    }

    return `| \`${pck.name}\` | [${pck.version}](https://github.com/${pck.name}/releases/tag/${pck.version}) | no change |`;
  })
  .join("\n");

console.log(`| Package name | Current Version | Previous Version |
  | --- | --- | --- |`);

console.log(results);' > run.js

node run.js > result.md

rm composer.latest.lock.json
rm composer.previous.lock.json
rm tags.json
rm run.js