<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>SCORM 1.2 </title>
  <script src="https://cdn.jsdelivr.net/npm/scorm-again@latest/dist/scorm-again.js"></script>
  <style>
    html,body,iframe { width: 100%; height: 100%; padding: 0; margin: 0; border: none}
  </style>
  <script type="text/javascript">
    var settings = @json($data);
    window.API = new Scorm12API(settings.player);
    window.API.on('LMSSetValue.cmi.*', function(CMIElement, value) {
      // TODO push this data though post message
        console.log(arguments);
    });
  </script>
</head>

<body>
  <iframe src={{ $data['entry_url_absolute'] }}></iframe>
</body>

</html>
