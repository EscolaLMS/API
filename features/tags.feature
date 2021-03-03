Feature: As Anonymous i want to fetch all tags
  In order to find courses
  As a Anonymous
  I want to read tags

  Scenario: Show all tags
    Given there is "frontend" tag created
    And there is "backend" tag created
    When anonymous is on "/api/tags" page
    Then response will have 200 code
    And the "Content-Type" header should be "application/json"
    And the anonymous sees
      """
      "title":"frontend"
      """
    And the anonymous sees
      """
      "title":"backend"
      """


  Scenario: All tags are unique
    Given there is "frontend" tag created 10 times
    And there is "backend" tag created 10 times
    When anonymous is on "/api/tags/unique" page
    Then response will have 200 code
    And the "Content-Type" header should be "application/json"
    And all tags are unique
