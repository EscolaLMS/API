import "cypress-plugin-stripe-elements";

describe("buying course API end-to-end test", () => {
  // variables store between test steps
  let token;
  let paymentMethodId;
  let course;

  const email = "student@escola-lms.com";

  // test step helpers, mostly shorts for `cy.request`
  const submitLogin = (email, password) =>
    cy.request("POST", `/auth/login`, { email, password });

  const getProfile = () =>
    cy.request({
      method: "GET",
      url: `/profile/me`,
      auth: {
        bearer: token,
      },
    });

  const findCourses = () =>
    cy.request({
      method: "GET",
      url: `/courses`,
      auth: {
        bearer: token,
      },
    });

  const addCourseToCart = () =>
    cy.request({
      method: "POST",
      url: "/cart/course/" + course,
      auth: {
        bearer: token,
      },
    });

  const loadCart = () =>
    cy.request({
      method: "GET",
      url: "/cart",
      auth: {
        bearer: token,
      },
    });

  const payForCart = () =>
    cy.request({
      method: "POST",
      url: "/cart/pay",
      auth: {
        bearer: token,
      },
      body: {
        paymentMethodId: paymentMethodId,
      },
    });

  const accessCourseProgram = () =>
    cy.request({
      method: "GET",
      url: "/courses/" + course + "/program",
      auth: {
        bearer: token,
      },
    });

  const accessCourseProgress = () =>
    cy.request({
      method: "GET",
      url: "/courses/progress",
      auth: {
        bearer: token,
      },
    });

  // all tests step by step

  // @test Login
  it("login", () => {
    submitLogin(email, "secret")
      .then((response) => {
        expect(response.body.data).to.have.property("token");
        token = response.body.data.token;
      })
      .its("headers")
      .its("content-type")
      .should("include", "application/json");
  });

  // @test find courses
  it("finds courses", () => {
    findCourses()
      .then((response) => {
        expect(response.body).to.have.property("data");
        cy.log(response.body);
        course = response.body.data[0].id;
      })
      .its("headers")
      .its("content-type")
      .should("include", "application/json");
  });

  // @test add course to shopping cart
  it("adds course to shopping cart", () => {
    addCourseToCart()
      .then((response) => {
        cy.log(JSON.stringify(response.body));
        expect(response.status).to.eq(200);
      })
      .its("headers")
      .its("content-type")
      .should("include", "application/json");
  });

  // @test loads cart
  it("loads cart", () => {
    loadCart()
      .then((response) => {
        expect(response.status).to.eq(200);
        expect(response.body.data.items[0].id).to.eq(course);
      })
      .its("headers")
      .its("content-type")
      .should("include", "application/json");
  });

  // @test creates payment method
  it("creates payment method", () => {
    cy.visit(Cypress.env("stripeTestUrl"));
    cy.get("#card-element").within(() => {
      cy.fillElementsInput("cardNumber", "4242424242424242");
      cy.fillElementsInput("cardExpiry", "1025");
      cy.fillElementsInput("cardCvc", "123");
      cy.fillElementsInput("postalCode", "80288");
    });
    cy.get("#submit-button").click();
    cy.get("#payment-method-id")
      .invoke("val")
      .should("not.be.empty")
      .then((val) => {
        cy.log(val);
        paymentMethodId = val;
      });
  });

  // @test pays for cart
  it("pays for cart", () => {
    payForCart()
      .then((response) => {
        expect(response.status).to.eq(200);
      })
      .its("headers")
      .its("content-type")
      .should("include", "application/json");
  });

  // @test has access to course progress

  it("has access to course progress", () => {
    accessCourseProgress()
      .then((response) => {
        const progress = response.body.data.find(
          (progress) => progress.course.id === course
        );
        expect(progress.progress).to.be.an("array");
      })
      .its("headers")
      .its("content-type")
      .should("include", "application/json");
  });
});
