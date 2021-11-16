<?php

namespace Moonwalking_Bits\Configuration\Configuration_Source\Fixtures;

use Moonwalking_Bits\Configuration\Configuration_Source\Abstract_File_Configuration_Source;

class Test_File_Configuration_Source extends Abstract_File_Configuration_Source {

	public function fetch(): array {
		$content = $this->fetch_content();

		return compact( 'content' );
	}
}
