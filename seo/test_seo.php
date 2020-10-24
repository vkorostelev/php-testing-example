<?php
    function getData($filename)
    {
        $csvFile = fopen($filename, 'r');

        while(!feof($csvFile))
        {
            $row = fgetcsv($csvFile,0,',','"');
            if (is_array($row))
            {
                yield mapData($row);
            }
        }

        fclose($csvFile);
    }

    function mapData(array $row): array
    {
        $columnMapping = [
            'url',
            'title',
            'description',
            'og:title',
            'og:description',
            'og:site_name',
            'og:image',
            'og:video',
            'og:url',
            'og:locale',
            'og:type'
        ];
        $data = array();

        foreach ($row as $index => $value) {
            $data[$columnMapping[$index]] = $value;
        }

        return array(
            'url' => $data['url'],
            'tags' => array_slice($data, 1)
        );
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
        $tags = array();

        $titleNode = $xpath->query('//title')->item(0);
        $tags['title'] = $titleNode ? $titleNode->nodeValue : null;

        $descriptionNode = $xpath->query('//meta[@name="description"]')->item(0);
        $tags['description'] = $descriptionNode ? $descriptionNode->getAttribute('content') : null;

        foreach($xpath->query("//meta[contains(@property, 'og:')]") as $node) {
            $tags[$node->getAttribute('property')] = $node->getAttribute('content');
        }

        return $tags;
    }

    function compareTag($tagName, $expected, $actual): bool
    {
        if (is_null($expected) || empty($expected)) {
            return true;
        }

        if ($actual !== $expected) {
            print("\n> \"${tagName}\": failed");
            print("\n>> expected: ${expected}");
            print("\n>> got: ${actual}");
            return false;
        }

        print("\n> \"${tagName}\": ОК");
        return true;
    }

    $tests = 0;
    $failedTests = 0;

    foreach( getData('./seo.csv') as $data ) {
        print("\nChecking URL: ${data['url']}");
        $html = makeRequest($data['url']);
        $actualTags = getTags($html);

        $results = array();

        foreach ($data['tags'] as $key => $value) {
            $results[] = compareTag($key, $value, isset($actualTags[$key]) ? $actualTags[$key] : null);
        }

        $failedTests += in_array(False, $results) ? 1 : 0;
        $tests += 1;
        print("\n----------");
    }

    $passedTests = $tests - $failedTests;
    print("\nOverall test results:\nall: ${tests}, success: ${passedTests}, fail: ${failedTests}.\n");

    if ($failedTests > 0) {
        exit(1);
    }

    exit(0);
?>
