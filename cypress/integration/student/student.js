/* eslint-env mocha */
describe("student API end-to-end test", () => {
  // variables store between test steps
  let token;
  const apiUrl = Cypress.env("API_URL") || "http://localhost:1000/api";
  const email = "student@escola-lms.com";

  // test step helpers, mostly shorts for `cy.request`
  const submitLogin = (email, password) =>
    cy.request("POST", `${apiUrl}/auth/login`, { email, password });

  const getProfile = () =>
    cy.request({
      method: "GET",
      url: `${apiUrl}/profile/me`,
      auth: {
        bearer: token,
      },
    });

  const getSettings = () =>
    cy.request({
      method: "GET",
      url: `${apiUrl}/profile/settings`,
      auth: {
        bearer: token,
      },
    });

  // all tests step by step

  // @test Login
  it("login", () => {
    submitLogin(email, "secret")
      .then((response) => {
        expect(response.body).to.have.property("token");
        token = response.body.token;
      })
      .its("headers")
      .its("content-type")
      .should("include", "application/json");
  });

  // @test profile data
  it("fetches its profile data", () => {
    getProfile()
      .then((response) => {
        expect(response.body).to.have.property("data");
        token = response.body.token;
      })
      .its("headers")
      .its("content-type")
      .should("include", "application/json");
  });

  // @test settings data
  it("fetches its settings data", () => {
    getSettings()
      .then((response) => {
        expect(response.body).to.have.property("setting");
      })
      .its("headers")
      .its("content-type")
      .should("include", "application/json");
  });

  // @test change profile data

  // @test change email (endpoint me-auth)

  // @test change password

  // @test change profile email (message will be send)

  // @test change profile settings

  // @test change profile settings
});
