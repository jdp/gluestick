require 'rubygems'
require 'rake'
require 'rake/testtask'

begin
  require 'jeweler'
  Jeweler::Tasks.new do |gemspec|
    gemspec.name = "gluestick"
    gemspec.summary = "A simple interface to the Glue API"
    gemspec.email = "jdp34@njit.edu"
    gemspec.homepage = "http://github.com/jdp/gluestick"
    gemspec.description = "A simple interface to the Glue API"
    gemspec.authors = ["Justin Poliey"]
  end
rescue LoadError
  puts "Jeweler not available. Install it with: sudo gem install technicalpickles-jeweler -s http://gems.github.com"
end

desc "Run basic tests"
Rake::TestTask.new("test_units") do |t|
  t.pattern = "test/*_test.rb"
  t.verbose = true
  t.warning = true
end
