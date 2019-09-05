---
home: true
heroImage: /hero-notext.png
actionText: Documentation
actionLink: /documentation/
---

<div class="features">
  <div class="feature">
    <h2>Active Record Querying</h2>
    <p>
    
Querying any of the available [Win32 Providers Classes](https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-provider) is as simple as `Model::query($connection)`.
    
</p>
  </div>
  <div class="feature">
    <h2>Easily Testable</h2>
    <p>

You can easily create a fake model to test your code without having to actually query a connection with `Scripting::fake($testCase)->win32model(Model::class);`. Any calls to the given model will now use a fake connection.
    
</p>
  </div>
  <div class="feature">
    <h2>Simple Configuration</h2>
    <p>
    
The only  thing that you need to provide is a Connection.
    
</p>
  </div>
</div>