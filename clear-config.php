<?php

if (posix_geteuid() != 0) {
    throw new Exception("This script must be ran as a root user");
}

$source = $argv[1] ?? '';
$destination = $argv[2] ?? '';

if ($source == '') {
    throw new Exception("Please specify the source file");
}
if (!file_exists($source)) {
    throw new Exception("File $source does not exist");
}
if (!is_resource($i_stream = fopen($source, 'r'))) {
    throw new Exception("File $source is not readable");
}
if (!is_resource($o_stream = ($destination == '') ? STDIN : fopen($destination, 'w'))) {
    throw new Exception("File $destination is not writeable");
}

$delimiter = ['#', ';', '\\\\'];

while (!feof($i_stream)) {
    $line =  trim(fgets($i_stream));
    $comment = ($line == '');
    foreach($delimiter as $delim) {
        if (str_starts_with($line, $delim)) {
            $comment = true;
        }
    }
    if (!$comment) {
        fwrite($o_stream, $line . PHP_EOL);
    }
}

