module.exports = app => {
    const products = require("../controllers/product.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();
  
    // Create a new Product
    router.post("/",adminMiddleware.isAuthenticated ,products.create);
  
    // Retrieve all products
    router.get("/", products.findAll);
  
    // Retrieve a single Product with id
    router.get("/:id", products.findOne);
  
    // Update a Product with id
    router.put("/:id", products.update);
  
    // Delete a Product with id
    router.delete("/", adminMiddleware.isAuthenticated ,products.delete);
  
  
    app.use('/api/product', router);
  };