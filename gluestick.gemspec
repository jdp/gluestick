Gem::Specification.new do |s|
  s.name = %q{gluestick}
  s.version = "0.1.0"
  s.date = %q{2009-05-20}
  s.authors = ["Justin Poliey"]
  s.email = %q{jdp34@njit.edu}
  s.summary = %q{Gluestick provides a simple and straightforward interface to the Glue API.}
  s.homepage = %q{http://github.com/jdp/gluestick/tree/master}
  s.description = %q{Gluestick provides a simple and straightforward interface to the Glue API.}
  s.requirements << 'addressable, 2.1.0 or greater'
  s.add_dependency 'addressable', '>= 2.1.0'
  s.files = ["README", "LICENSE", "lib/gluestick.rb"]
end
