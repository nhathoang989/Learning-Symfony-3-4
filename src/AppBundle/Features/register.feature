Feature: Handle user registration via the RESTful API

  In order to allow a user to sign up
  As a client software developer
  I need to be able to handle registration


  Background:
    Given there are Users with the following details:
      | id | username | email          | password | phone_number  |
      | 1  | peter    | peter@test.com | testpass | 123456789     |
    And I set header "Content-Type" with value "application/json"


  Scenario: Can register with valid data
    When I send a "POST" request to "/register" with body:
      """
      {
        "email": "gary@test.co.uk",
        "phoneNumber": "123123123",
        "username": "garold",
        "plainPassword": {
          "first": "gaz123",
          "second": "gaz123"
        }
      }
      """
    Then the response code should be 200
     And the response should contain json:
      """
      {
          "success": true
      }
      """

  Scenario: Cannot register with existing user name
    When I send a "POST" request to "/register" with body:
      """
      {
        "email": "gary@test.co.uk",
        "phoneNumber": "123123123",
        "username": "peter",
        "plainPassword": {
          "first": "gaz123",
          "second": "gaz123"
        }
      }
      """
    Then the response code should be 200
    And the response should contain json:
      """
      {
          "success": false,
          "errors": "fos_user.username.already_used"
      }
      """

  Scenario: Cannot register with existing email
    When I send a "POST" request to "/register" with body:
      """
      {
        "email": "peter@test.com",
        "phoneNumber": "123123123",
        "username": "gary",
        "plainPassword": {
          "first": "gaz123",
          "second": "gaz123"
        }
      }
      """
    Then the response code should be 200
    And the response should contain json:
      """
      {
          "success": false,
          "errors": "fos_user.email.already_used"
      }
      """

  Scenario: Cannot register with unmatch password
    When I send a "POST" request to "/register" with body:
      """
      {
        "email": "gary@test.co.uk",
        "phoneNumber": "123123123",
        "username": "gary",
        "plainPassword": {
          "first": "gaz1234",
          "second": "gaz123"
        }
      }
      """
    Then the response code should be 200
    And the response should contain json:
      """
      {
          "success": false,
          "errors": "fos_user.password.mismatch"
      }
      """

  Scenario: Cannot register with existed phone_number
    When I send a "POST" request to "/register" with body:
      """
      {
        "email": "gary@test.co.uk",
        "phoneNumber": "123456789",
        "username": "gary",
        "plainPassword": {
          "first": "gaz1234",
          "second": "gaz123"
        }
      }
      """
    Then the response code should be 200
    And the response should contain json:
      """
      {
          "success": false,
          "errors": [
              "phoneNumber existed!"
          ]
      }
      """
