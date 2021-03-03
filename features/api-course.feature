Feature: As Anonymous i want to fetch all courses
  In order to find courses
  As a Anonymous
  I want to read courses

  Scenario: Show all courses
    Given there is "Photography - Become a Better Photographer" course created
    When anonymous is on "/api/courses" page
    Then response will have 200 code
    And the "Content-Type" header should be "application/json"
    And the anonymous sees
      """
      "course_title":"Photography - Become a Better Photographer"
      """

  Scenario: Find course by title
    Given there is "Photography - Become a Better Photographer" course created
    When anonymous is on "/api/courses?course_title=Photography" page
    Then response will have 200 code
    And the "Content-Type" header should be "application/json"
    And the anonymous sees
      """
      "course_title":"Photography - Become a Better Photographer"
      """

  Scenario: Find course by category
    Given there is "Photography" category created with id 666
    And there is "Analog" subcategory created with id 667 and parentid 666
    And there is "Photography - Become a Better Photographer" course created
    And course "Photography - Become a Better Photographer"  has category id 666
    When anonymous is on "/api/courses?category_id=666" page
    Then response will have 200 code
    And the "Content-Type" header should be "application/json"
    And the anonymous sees
      """
      "course_title":"Photography - Become a Better Photographer"
      """

  Scenario: Find course with parent category by category
    Given there is "Photography" category created with id 666
    And there is "Analog" subcategory created with id 667 and parentid 666
    And there is "Photography - Become a Better Photographer" course created
    And course "Photography - Become a Better Photographer"  has category id 667
    When anonymous is on "/api/courses?category_id=666" page
    Then response will have 200 code
    And the "Content-Type" header should be "application/json"
    And the anonymous sees
      """
      "course_title":"Photography - Become a Better Photographer"
      """

  Scenario: Find course by tag
    And there is "Photography - Become a Better Photographer" course created
    And course "Photography - Become a Better Photographer"  has Tag "photography"
    When anonymous is on "/api/courses?tag=photography" page
    Then response will have 200 code
    And the "Content-Type" header should be "application/json"
    And the anonymous sees
      """
      "course_title":"Photography - Become a Better Photographer"
      """

