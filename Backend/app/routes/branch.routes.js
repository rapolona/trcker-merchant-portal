module.exports = app => {
    const branches = require("../controllers/branch.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();
  
    // Create a new Branch
    router.post("/", adminMiddleware.isAuthenticated,branches.create);
  
    // // Retrieve all branches
    // router.get("/",branches.findAll);

    // Create bulk
    router.post("/bulk_add", adminMiddleware.isAuthenticated,branches.createMany);


  
    // Retrieve a single Branch with id
    router.get("/find_one/:branch_id",adminMiddleware.isAuthenticated ,branches.findOne);
  
    // Update a Branch with id
    router.put("/", branches.update);
  
    // Delete a Branch with id
    router.delete("/", adminMiddleware.isAuthenticated ,branches.delete);

    // Get all distinct items from each filterable column
    router.get("/filters", adminMiddleware.isAuthenticated,branches.findDistinctFilters);
  

  
    app.use('/merchant/branch/', router);
  };