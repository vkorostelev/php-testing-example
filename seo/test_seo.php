<?php
    function getData($filename)
    {
        $csvFile = fopen($filename, 'r');

        while(!feof($csvFile))
        {
            $line = fgetcsv($csvFile,0,',','"');
            if (is_array($line))
            {
                yield $line;
            }
        }

        fclose($csvFile);
    }

    function makeRequest($url)
    {
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Cookie: test=seo\r\n"
            )
        );
        $context = stream_context_create($opts);
        return file_get_contents($url, false, $context);
    }

    function getTags(string $html)
    {
        $document = new DOMDocument('1.0','UTF-8');
        libxml_use_internal_errors(true);
        $document->loadHTML($html);
        libxml_clear_errors();
        $xpath = new DOMXPath($document);

        $titleNode = $xpath->query('//title')->item(0);
        $title = $titleNode ? $titleNode->nodeValue : '';

        $descriptionNode = $xpath->query('//meta[@name="description"]')->item(0);
        $description = $descriptionNode ? $descriptionNode->getAttribute('content') : '';

        return [$title, $description];
    }

    function compareTag($tagName, $expected, $actual): bool
    {

        if ($actual !== $expected)
        {
            print("\n> ${tagName}: Failed");
            print("\n>> Expected: ${expected}");
            print("\n>> Got: ${actual}");
            return False;
        }

        print("\n> ${tagName}: ОК");
        return True;
    }

    $tests = 0;
    $failedTests = 0;

    foreach( getData('./seo.csv') as [$url, $title, $description] ) {
        print("\nChecking URL: ${url}");
        $html = makeRequest($url);
        [$actual_title, $actual_description] = getTags($html);
        $titleResult = compareTag('Title', $title, $actual_title);
        $descriptionResult = compareTag('Description', $description, $actual_description);
        $failedTests += $titleResult && $descriptionResult ? 0 : 1;
        $tests += 1;
        print("\n----------");
    }

    $passedTests = $tests - $failedTests;

    print("\nOverall test results:\nall: ${tests}, success: ${passedTests}, fail: ${failedTests}.\n");

    if ($failedTests > 0){
        exit(1);
    }

    exit(0);
?>
