Feature: Handle user login via the RESTful API

  In order to allow secure access to the system
  As a client software developer
  I need to be able to let users log in and out


  Background:
    Given there are Clients with the following details:
      | random_id                                          | redirect_uris | secret                                             | allowed_grant_types     |
      | 3bcbxd9e24g0gk4swg0kwgcwg4o8k8g4g888kwc44gcc0gwwk4 |               | 4ok2x70rlfokc8g0wws8c8kwcokw80k44sg48goc0ok4w0so0k | password, refresh_token |
    Given there are Users with the following details:
      | id | username | email          | password | phone_number  |
      | 1  | peter    | peter@test.com | testpass | 1234567891    |
      | 2  | john     | john@test.org  | johnpass | 1234567892    |
      | 3  | tim      | tim@blah.net   | timpass  | 1234567893    |
     And I set header "Content-Type" with value "application/json"

@this
  Scenario: Cannot GET Login
    When I send a "GET" request to "/oauth/v2/token"
    Then the response code should be 400

  Scenario: User cannot Login with bad credentials
    When I send a "POST" request to "/oauth/v2/token" with body:
      """
      {
        "grant_type": "password",
        "client_id": "1_3bcbxd9e24g0gk4swg0kwgcwg4o8k8g4g888kwc44gcc0gwwk4",
        "client_secret": "4ok2x70rlfokc8g0wws8c8kwcokw80k44sg48goc0ok4w0so0k_wrong",
        "username": "jimmy",
        "password": "badpass"
      }
      """
    Then the response code should be 400
      And the response should contain json:
        """
        {
            "success": false
        }
        """

  Scenario: User can Login with good credentials (username)
    When I send a "POST" request to "/oauth/v2/token" with body:
      """
      {
        "grant_type": "password",
        "client_id": "1_3bcbxd9e24g0gk4swg0kwgcwg4o8k8g4g888kwc44gcc0gwwk4",
        "client_secret": "4ok2x70rlfokc8g0wws8c8kwcokw80k44sg48goc0ok4w0so0k",
        "username": "peter",
        "password": "testpass"
      }
      """
    Then the response code should be 200
    And the response should contain json:
        """
        {
            "success": true
        }
        """
