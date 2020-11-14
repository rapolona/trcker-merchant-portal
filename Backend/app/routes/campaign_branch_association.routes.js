module.exports = app => {
    const campaign_branch_associations = require("../controllers/campaign_branch_association.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();
  
    // Create a new Campaign Branch Association
    router.post("/",campaign_branch_associations.create);
  
    // Retrieve all campaign_branch_associations
    router.get("/", campaign_branch_associations.findAll);
  
    // Retrieve a single Campaign Branch Association with id
    router.get("/:id", campaign_branch_associations.findOne);
  
    // Update a Campaign Branch Association with id
    router.put("/:id", campaign_branch_associations.update);
  
    // Delete a Campaign Branch Association with id
    router.delete("/:id", campaign_branch_associations.delete);
  
    // Create a new Campaign Branch Association
    router.delete("/", campaign_branch_associations.deleteAll);
  
    app.use('/api/campaign_branch_association', router);
  };