Feature: As Anonymous i want to fetch all categories
  In order to find courses
  As a Anonymous
  I want to read category

  Scenario: Show all categories
    Given there is "Lorem IPSUM" category created
    When anonymous is on "/api/categories" page
    Then response will have 200 code
    And the "Content-Type" header should be "application/json"
    And the anonymous sees
      """
      "name":"Lorem IPSUM"
      """
    And the anonymous sees
      """
      "slug":"lorem-ipsum"
      """

