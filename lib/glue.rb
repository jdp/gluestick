require 'rubygems'
require 'net/http'
require 'addressable/uri'

class GlueError < RuntimeError
  attr_reader :message

  def initialize message
    @message = message
  end
end

class Glue
  @method_family = nil
  @username = nil
  @password = nil

  def initialize(username, password)
    @username = username
    @password = password
  end

  def method_missing(name, *args)
    if @method_family == nil
      @method_family = name
      self
    else
      method = @method_family.to_s + '/' + name.to_s
      params = nil
      if args.first.is_a?(Hash)
        uri = Addressable::URI.new
        uri.query_values = args.first
        params = uri.query
      end
      Net::HTTP.start('api.getglue.com') do |http|
        req = Net::HTTP::Get.new('/v1/%s?%s' % [method, params])
        req.basic_auth @username, @password
        @response = http.request(req)
      end
      raise GlueError.new(@response.body) if not @response.is_a?(Net::HTTPSuccess)
      @response.body
    end
  end
end
