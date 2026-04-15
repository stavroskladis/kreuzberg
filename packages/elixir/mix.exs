defmodule Kreuzberg.MixProject do
  use Mix.Project

  def project do
    [
      app: :kreuzberg,
      version: "4.8.5",
      elixir: "~> 1.14",
      description: "High-performance document intelligence library",
      package: package(),
      deps: deps()
    ]
  end

  defp package do
    [
      licenses: ["Elastic-2.0"],
      links: %{"GitHub" => "https://github.com/kreuzberg-dev/kreuzberg"}
    ]
  end

  defp deps do
    [
      {:rustler, "~> 0.34"},
      {:credo, "~> 1.7", only: [:dev, :test], runtime: false},
      {:ex_doc, "~> 0.40", only: :dev, runtime: false}
    ]
  end
end
