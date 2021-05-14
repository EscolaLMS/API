/* eslint-env mocha */
import faker from "faker";
import libmime from "libmime";
import quotedPrintable from "quoted-printable";

describe("student API end-to-end test", () => {
  // variables store between test steps
  let token;
  let verificationLink;

  const getRegisterData = () => ({
    first_name: faker.name.firstName(),
    last_name: faker.name.lastName(),
    email: faker.internet.email(),
    password: "secret123",
    password_confirmation: "secret123",
  });

  const verificationLinkRegExp =
    /auth\/email\/verify\/(.|\n)*?signature=(.)*/gm;

  const registerData = getRegisterData();

  const submitRegister = (input) => cy.request("POST", `/auth/register`, input);

  // test step helpers, mostly shorts for `cy.request`
  const submitLogin = (email, password) =>
    cy.request({
      failOnStatusCode: false,
      method: "POST",
      url: `/auth/login`,
      body: { email, password },
    });

  const getProfile = () =>
    cy.request({
      method: "GET",
      url: `/profile/me`,
      auth: {
        bearer: token,
      },
    });

  const getSettings = () =>
    cy.request({
      method: "GET",
      url: `/profile/settings`,
      auth: {
        bearer: token,
      },
    });

  // all tests step by step

  // @test Login
  it("register with dummy data ", () => {
    submitRegister(registerData)
      .then((response) => {
        expect(response.body).to.have.property("success");
      })
      .its("headers")
      .its("content-type")
      .should("include", "application/json");
  });

  // @test account is unverified so far

  it("should not be able to login right now", () => {
    submitLogin(registerData.email, registerData.password)
      .should((response) => {
        expect(response.status).to.eq(422);
      })
      .its("headers")
      .its("content-type")
      .should("include", "application/json");
  });

  // @test email should have been sent
  it(`email to ${registerData.email} has been sent`, () => {
    const email = cy.mhGetMailsByRecipient(registerData.email);
    email.should("have.length.at.least", 1);

    email
      .mhFirst()
      .mhGetBody()
      .then((v) => {
        //const content = libmime.decodeFlowed(v);
        const content = quotedPrintable.decode(v);
        verificationLink = content.match(verificationLinkRegExp)[0];
        expect(verificationLink).have.length.at.least(50);
      });
  });

  // @test clicking on verification
  it(`clicks on verification link from ${registerData.email} that verifies address and redirects`, () => {
    cy.request({
      url: `/${verificationLink}`,
      failOnStatusCode: false,
      followRedirect: true, // turn off following redirects
    });
  });

  // @test login should work now account is verified
  it("should be able to login right now", () => {
    submitLogin(registerData.email, registerData.password)
      .should((response) => {
        expect(response.status).to.eq(200);
        token = response.body.token;
      })
      .its("headers")
      .its("content-type")
      .should("include", "application/json");
  });

  /*

  // @test profile data
  it("fetches registered account profile data", () => {
    getProfile()
      .then((response) => {
        expect(response.body).to.have.property("data");
      })
      .its("headers")
      .its("content-type")
      .should("include", "application/json");
  });

  // @test settings data
  it("fetches registered account profile ", () => {
    getSettings()
      .then((response) => {
        expect(response.body).to.have.property("setting");
      })
      .its("headers")
      .its("content-type")
      .should("include", "application/json");
  });

  */
});
