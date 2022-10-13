<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class testURL extends TestCase
{
    private string $API = "http://localhost";
    private string $response = "HTTP/1.1 302 Found";

    /**
     * @return void
     */
    public function testRedirects(): void
    {
        file_get_contents("$this->API/getProfessors.php");
        $this->assertEquals($http_response_header[0], $this->response);
        file_get_contents("$this->API/getGroups.php");
        $this->assertEquals($http_response_header[0], $this->response);
        file_get_contents("$this->API/getTimetable.php");
        $this->assertEquals($http_response_header[0], $this->response);
        file_get_contents("$this->API/getTimetable.php");
        $this->assertEquals($http_response_header[0], $this->response);
        file_get_contents("$this->API/getTimetable.php?professor=0x80EC000C295831C111ED292993338C90");
        $this->assertEquals($http_response_header[0], $this->response);
        file_get_contents("$this->API/getTimetable.php?group=0x80C3000C295831B711E7F77FAF8F411E");
        $this->assertEquals($http_response_header[0], $this->response);
        file_get_contents("$this->API/getTimetable.php?group=0x80C3000C295831B711E7F77FAF8F411E&professor=0x80EC000C295831C111ED292993338C90");
        $this->assertEquals($http_response_header[0], $this->response);
    }
}
