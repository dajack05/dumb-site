{ pkgs ? import <nixpkgs> {} }:
  pkgs.mkShell {
    nativeBuildInputs = with pkgs.buildPackages; [ 
      nodejs_23
      php84
      php84Packages.composer
    ];
  }
