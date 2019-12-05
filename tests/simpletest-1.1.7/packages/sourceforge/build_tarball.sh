#!/bin/bash

# Builds project release.
#
cd ../../..

NAME=simpletest/packages/sourceforge/simpletest_`cat simpletest/VERSION`.tar.gz
FILES=(simpletest/README \
          simpletest/VERSION \
          simpletest/LICENSE \
          simpletest/HELP_MY_TESTS_DONT_WORK_ANYMORE \
          simpletest/arguments.php \
          simpletest/authentication.php \
          simpletest/autorun.php \
          simpletest/browser.php \
          simpletest/collector.php \
          simpletest/compatibility.php \
          simpletest/cookies.php \
          simpletest/default_reporter.php \
          simpletest/detached.php \
          simpletest/dumper.php \
          simpletest/eclipse.php \
          simpletest/encoding.php \
          simpletest/errors.php \
          simpletest/exceptions.php \
          simpletest/expectation.php \
          simpletest/form.php \
          simpletest/frames.php \
          simpletest/http.php \
          simpletest/invoker.php \
          simpletest/mock_objects.php \
          simpletest/page.php \
          simpletest/php_parser.php \
          simpletest/recorder.php \
          simpletest/reflection_php4.php \
          simpletest/reflection_php5.php \
          simpletest/remote.php \
          simpletest/reporter.php \
          simpletest/scorer.php \
          simpletest/selector.php \
          simpletest/shell_tester.php \
          simpletest/simpletest.php \
          simpletest/socket.php \
          simpletest/tag.php \
          simpletest/test_case.php \
          simpletest/tidy_parser.php \
          simpletest/unit_tester.php \
          simpletest/url.php \
          simpletest/user_agent.php \
          simpletest/web_tester.php \
          simpletest/xml.php \
          simpletest/extensions/pear_test_case.php \
          simpletest/extensions/testdox.php \
          simpletest/extensions/testdox/test.php \
          simpletest/test/acceptance_test.php \
          simpletest/test/adapter_test.php \
          simpletest/test/all_tests.php \
          simpletest/test/arguments_test.php \
          simpletest/test/authentication_test.php \
          simpletest/test/bad_test_suite.php \
          simpletest/test/browser_test.php \
          simpletest/test/collector_test.php \
          simpletest/test/command_line_test.php \
          simpletest/test/compatibility_test.php \
          simpletest/test/cookies_test.php \
          simpletest/test/detached_test.php \
          simpletest/test/dumper_test.php \
          simpletest/test/eclipse_test.php \
          simpletest/test/encoding_test.php \
          simpletest/test/errors_test.php \
          simpletest/test/exceptions_test.php \
          simpletest/test/expectation_test.php \
          simpletest/test/form_test.php \
          simpletest/test/frames_test.php \
          simpletest/test/http_test.php \
          simpletest/test/interfaces_test.php \
          simpletest/test/interfaces_test_php5_1.php \
          simpletest/test/live_test.php \
          simpletest/test/mock_objects_test.php \
          simpletest/test/page_test.php \
          simpletest/test/parse_error_test.php \
          simpletest/test/php_parser_test.php \
          simpletest/test/parsing_test.php \
          simpletest/test/parsing_test.php \
          simpletest/test/recorder_test.php \
          simpletest/test/reflection_php5_test.php \
          simpletest/test/remote_test.php \
          simpletest/test/shell_test.php \
          simpletest/test/shell_tester_test.php \
          simpletest/test/simpletest_test.php \
          simpletest/test/socket_test.php \
          simpletest/test/tag_test.php \
          simpletest/test/test_with_parse_error.php \
          simpletest/test/unit_tests.php \
          simpletest/test/unit_tester_test.php \
          simpletest/test/autorun_test.php \
          simpletest/test/url_test.php \
          simpletest/test/user_agent_test.php \
          simpletest/test/visual_test.php \
          simpletest/test/web_tester_test.php \
          simpletest/test/xml_test.php \
          simpletest/test/support/collector/collectable.1 \
          simpletest/test/support/collector/collectable.2 \
          simpletest/test/support/upload_sample.txt \
          simpletest/test/support/supplementary_upload_sample.txt \
          simpletest/test/support/latin1_sample \
          simpletest/test/support/spl_examples.php \
          simpletest/test/support/empty_test_file.php \
          simpletest/test/support/test1.php \
          simpletest/test/support/failing_test.php \
          simpletest/test/support/passing_test.php \
          simpletest/test/support/recorder_sample.php \
          simpletest/test/site/file.html \
          simpletest/docs/en/docs.css \
          simpletest/docs/en/index.html \
          simpletest/docs/en/overview.html \
          simpletest/docs/en/unit_test_documentation.html \
          simpletest/docs/en/group_test_documentation.html \
          simpletest/docs/en/mock_objects_documentation.html \
          simpletest/docs/en/partial_mocks_documentation.html \
          simpletest/docs/en/reporter_documentation.html \
          simpletest/docs/en/expectation_documentation.html \
          simpletest/docs/en/web_tester_documentation.html \
          simpletest/docs/en/form_testing_documentation.html \
          simpletest/docs/en/authentication_documentation.html \
          simpletest/docs/en/browser_documentation.html \
          simpletest/docs/fr/docs.css \
          simpletest/docs/fr/index.html \
          simpletest/docs/fr/overview.html \
          simpletest/docs/fr/unit_test_documentation.html \
          simpletest/docs/fr/group_test_documentation.html \
          simpletest/docs/fr/mock_objects_documentation.html \
          simpletest/docs/fr/partial_mocks_documentation.html \
          simpletest/docs/fr/reporter_documentation.html \
          simpletest/docs/fr/expectation_documentation.html \
          simpletest/docs/fr/web_tester_documentation.html \
          simpletest/docs/fr/form_testing_documentation.html \
          simpletest/docs/fr/authentication_documentation.html \
          simpletest/docs/fr/browser_documentation.html)

tar -zcf $NAME ${FILES[*]}
