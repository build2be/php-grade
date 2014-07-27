Feature: Generate reports
  In order to generate reports I need run the commands with different switches.

  Scenario: Run without arguments
    When I run "bin/php-grade"
    Then STDOUT should contain:
      """
      PHP Grade
      """
    And STDOUT should contain:
    """
    [options] command [arguments]
    """

  Scenario: Run test on directory without options.
    When I run "bin/php-grade run " with "tests/fixtures"
    Then STDOUT should contain:
      """
      Missing file doc comment
      """
    And STDOUT should not contain:
      """
      Running
      """
    And STDOUT should not contain:
      """
      Scanning for files:
      """

  Scenario: Run test on directory with verbose on.
    When I run "bin/php-grade run --verbose" with "tests/fixtures"
    Then STDOUT should contain:
      """
      Scanning for files:
      """
    And STDOUT should contain:
      """
      Running
      """

  Scenario: Run test on directory with more verbose
    When I run "bin/php-grade run -vv" with "tests/fixtures"
    Then STDOUT should contain:
      """
      2: Verbose level - 3 -
      """

  Scenario: Run test on directory with most verbose
    When I run "bin/php-grade run -vvv" with "tests/fixtures"
    Then STDOUT should contain:
      """
      2: Verbose level - 4 -
      """
