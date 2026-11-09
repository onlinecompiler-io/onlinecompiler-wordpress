<?php
/**
 * The languages OnlineCompiler.io runs, by compiler id.
 *
 * Copied from the service's compiler list at each release (onlinecompiler.io/docs/compilers).
 * The widget also accepts ids that were replaced by a newer version and maps them itself.
 *
 * @package OnlineCompiler
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Compiler id => array( label, short name ).
 *
 * The short name is what a shortcode can use instead of the id, such as lang="python".
 *
 * @return array<string, array{0: string, 1: string}>
 */
function onlinecompiler_languages() {
	return array(
		'python-3.14' => array( 'Python 3.14', 'python' ),
		'gcc-15' => array( 'C (GCC 15)', 'c' ),
		'g++-15' => array( 'C++ (G++ 15)', 'cpp' ),
		'openjdk-25' => array( 'Java (OpenJDK 25)', 'java' ),
		'dotnet-csharp-10' => array( 'C# (.NET SDK 10)', 'csharp' ),
		'dotnet-fsharp-10' => array( 'F# (.NET SDK 10)', 'fsharp' ),
		'go-1.26' => array( 'Go 1.26', 'go' ),
		'rust-1.93' => array( 'Rust 1.93', 'rust' ),
		'php-8.5' => array( 'PHP 8.5', 'php' ),
		'ruby-4.0' => array( 'Ruby 4.0', 'ruby' ),
		'haskell-9.12' => array( 'Haskell (GHC 9.12)', 'haskell' ),
		'typescript-deno' => array( 'TypeScript (Deno)', 'typescript' ),
		'nodejs-24' => array( 'JavaScript (Node.js 24)', 'javascript' ),
		'sqlite-3' => array( 'SQL (SQLite 3)', 'sql' ),
		'duckdb-1.5' => array( 'DuckDB 1.5', 'duckdb' ),
		'bash-5.2' => array( 'Bash 5.2', 'bash' ),
		'gfortran-15' => array( 'Fortran (gfortran 15)', 'fortran' ),
		'dotnet-vbnet-10' => array( 'Visual Basic (.NET SDK 10)', 'vbnet' ),
		'kotlin-2.4' => array( 'Kotlin 2.4', 'kotlin' ),
		'r-4.6' => array( 'R 4.6', 'r' ),
		'swift-6.4' => array( 'Swift 6.4', 'swift' ),
		'gnucobol-3.2' => array( 'COBOL (GnuCOBOL 3.2)', 'cobol' ),
		'perl-5.44' => array( 'Perl 5.44', 'perl' ),
		'julia-1.13' => array( 'Julia 1.13', 'julia' ),
		'fpc-3.2' => array( 'Pascal (Free Pascal 3.2)', 'pascal' ),
		'octave-11' => array( 'Octave 11', 'octave' ),
		'nasm-3' => array( 'Assembly (NASM 3)', 'assembly' ),
		'lua-5.5' => array( 'Lua 5.5', 'lua' ),
		'ocaml-5.4' => array( 'OCaml 5.4', 'ocaml' ),
		'sbcl-2.6' => array( 'Common Lisp (SBCL 2.6)', 'common-lisp' ),
		'erlang-29' => array( 'Erlang (OTP 29)', 'erlang' ),
		'elixir-1.20' => array( 'Elixir 1.20', 'elixir' ),
		'swipl-10' => array( 'Prolog (SWI-Prolog 10)', 'prolog' ),
		'ldc-1.43' => array( 'D (LDC 1.43)', 'd' ),
		'fbc-1.10' => array( 'FreeBASIC 1.10', 'freebasic' ),
		'dart-3.13' => array( 'Dart 3.13', 'dart' ),
		'clojure-1.12' => array( 'Clojure 1.12', 'clojure' ),
		'groovy-6.0' => array( 'Groovy 6.0', 'groovy' ),
		'scala-3.10' => array( 'Scala 3.10', 'scala' ),
		'zig-0.17' => array( 'Zig 0.17', 'zig' ),
		'nim-2.2' => array( 'Nim 2.2', 'nim' ),
		'crystal-1.22' => array( 'Crystal 1.22', 'crystal' ),
		'gnat-15' => array( 'Ada (GNAT 15)', 'ada' ),
		'powershell-7.6' => array( 'PowerShell 7.6', 'powershell' ),
		'racket-9.3' => array( 'Racket 9.3', 'racket' ),
		'clang-objc-21' => array( 'Objective-C (Clang 21)', 'objective-c' ),
		'g++-15-cpp20' => array( 'C++20 (G++ 15)', 'cpp' ),
		'python-3.14-pandas2' => array( 'Python 3.14 with pandas 2', 'python' ),
	);
}

/**
 * Turns what an author wrote (an id, a short name such as "python", or a common alias)
 * into a compiler id. Anything unknown is passed on unchanged for the widget to judge.
 *
 * @param string $lang What the author wrote.
 * @return string A compiler id, or '' for none.
 */
function onlinecompiler_resolve_language( $lang ) {
	$lang = strtolower( trim( (string) $lang ) );
	if ( '' === $lang ) {
		return '';
	}
	$languages = onlinecompiler_languages();
	if ( isset( $languages[ $lang ] ) ) {
		return $lang;
	}
	$aliases = array(
		'py'         => 'python-3.14',
		'c++'        => 'g++-15',
		'js'         => 'nodejs-24',
		'node'       => 'nodejs-24',
		'ts'         => 'typescript-deno',
		'c#'         => 'dotnet-csharp-10',
		'cs'         => 'dotnet-csharp-10',
		'f#'         => 'dotnet-fsharp-10',
		'golang'     => 'go-1.26',
		'sqlite'     => 'sqlite-3',
		'sh'         => 'bash-5.2',
		'shell'      => 'bash-5.2',
		'vb'         => 'dotnet-vbnet-10',
		'asm'        => 'nasm-3',
		'lisp'       => 'sbcl-2.6',
		'objc'       => 'clang-objc-21',
		'cpp20'      => 'g++-15-cpp20',
		'c++20'      => 'g++-15-cpp20',
	);
	if ( isset( $aliases[ $lang ] ) ) {
		return $aliases[ $lang ];
	}
	// The first id with that short name: python-3.14 for "python", g++-15 for "cpp".
	foreach ( $languages as $id => $language ) {
		if ( $language[1] === $lang ) {
			return $id;
		}
	}
	return $lang;
}
