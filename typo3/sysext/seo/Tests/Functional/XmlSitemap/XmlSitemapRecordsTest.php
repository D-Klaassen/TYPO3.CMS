<?php
declare(strict_types = 1);

namespace TYPO3\CMS\Seo\Tests\Functional\XmlSitemap;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Frontend\Tests\Functional\SiteHandling\AbstractTestCase;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;

/**
 * Contains functional tests for the XmlSitemap Index
 */
class XmlSitemapRecordsTest extends AbstractTestCase
{
    /**
     * @var string[]
     */
    protected $coreExtensionsToLoad = [
        'core',
        'frontend',
        'seo'
    ];

    protected function setUp()
    {
        parent::setUp();
        $this->importDataSet('EXT:seo/Tests/Functional/Fixtures/pages-sitemap.xml');
        $this->importDataSet('EXT:seo/Tests/Functional/Fixtures/sys_category.xml');
        $this->importDataSet('EXT:seo/Tests/Functional/Fixtures/tt_content.xml');
        $this->setUpFrontendRootPage(
            1,
            [
                'constants' => ['EXT:seo/Configuration/TypoScript/XmlSitemap/constants.typoscript'],
                'setup' => [
                    'EXT:seo/Configuration/TypoScript/XmlSitemap/setup.typoscript',
                    'EXT:seo/Tests/Functional/Fixtures/records.typoscript',
                    'EXT:seo/Tests/Functional/Fixtures/content.typoscript'
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function checkIfSiteMapIndexContainsSysCategoryLinks(): void
    {
        $this->writeSiteConfiguration(
            'website-local',
            $this->buildSiteConfiguration(1, 'http://localhost/'),
            [
                $this->buildDefaultLanguageConfiguration('EN', '/'),
            ]
        );

        $response = $this->executeFrontendRequest(
            (new InternalRequest('http://localhost/'))->withQueryParameters(
                [
                    'id' => 1,
                    'type' => 1533906435,
                    'sitemap' => 'records',
                ]
            )
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('Content-Length', $response->getHeaders());
        $stream = $response->getBody();
        $stream->rewind();
        $content = $stream->getContents();
        self::assertContains('http://localhost/?tx_example_category%5Bid%5D=1&amp;', $content);
        self::assertContains('http://localhost/?tx_example_category%5Bid%5D=2&amp;', $content);
        self::assertContains('<priority>0.5</priority>', $content);

        $this->assertGreaterThan(0, $response->getHeader('Content-Length')[0]);
    }

    /**
     * @test
     */
    public function checkIfSiteMapIndexContainsCustomChangeFreqAndPriorityValues(): void
    {
        $this->writeSiteConfiguration(
            'website-local',
            $this->buildSiteConfiguration(1, 'http://localhost/'),
            [
                $this->buildDefaultLanguageConfiguration('EN', '/'),
            ]
        );

        $response = $this->executeFrontendRequest(
            (new InternalRequest('http://localhost/'))->withQueryParameters(
                [
                    'id' => 1,
                    'type' => 1533906435,
                    'sitemap' => 'content',
                ]
            )
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('Content-Length', $response->getHeaders());
        $stream = $response->getBody();
        $stream->rewind();
        $content = $stream->getContents();

        self::assertContains('<changefreq>hourly</changefreq>', $content);
        self::assertContains('<priority>0.7</priority>', $content);

        $this->assertGreaterThan(0, $response->getHeader('Content-Length')[0]);
    }
}
