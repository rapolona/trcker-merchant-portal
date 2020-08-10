module.exports = app => {
    const branches = require("../controllers/branch.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();
  
    // Create a new Branch
    router.post("/", adminMiddleware.isAuthenticated,branches.create);
  
    // Retrieve all branches
    router.get("/",branches.findAll);
  
    // // Retrieve a single Branch with id
    // router.get("/:id", branches.findOne);
  
    // Update a Branch with id
    router.put("/", branches.update);
  
    // Delete a Branch with id
    router.delete("/:id", branches.delete);
  
    // Create a new Branch
    router.delete("/", branches.deleteAll);
  
    app.use('/api/branch', router);
  };