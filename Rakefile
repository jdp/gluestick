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
    gemspec.add_dependency "httparty", ">= 0.4.3"
  end
rescue LoadError
  puts "Jeweler not available. Install it with: sudo gem install technicalpickles-jeweler -s http://gems.github.com"
end

desc "Run basic tests"
Rake::TestTask.new("test") do |t|
  t.pattern = "test/*_test.rb"
  t.verbose = true
  # HTTParty generates tons of warnings
  t.warning = false
end
