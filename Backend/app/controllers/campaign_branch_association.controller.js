const db = require("../models");
const Campaign_Branch_Association = db.campaign_branch_associations;
const Op = db.Sequelize.Op;

// Create and Save a new Campaign_Branch_Association
exports.create = (req, res) => {
    // Validate request
    if (!req.body.campaign_id) {
      res.status(400).send({
        message: "Content can not be empty!"
      });
      return;
    }
  
    // Create a campaign_branch_association
    const campaign_branch_association = {
      campaign_id: req.body.campaign_id,
      branch_id: req.body.branch_id,
      respondent_count: req.body.respondent_count
    };

    console.log(campaign_branch_association)
  
    // Save Campaign_Branch_Association in the database
    Campaign_Branch_Association.create(campaign_branch_association)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Campaign_Branch_Association."
        });
      });
  };

// Retrieve all Branches from the database.
exports.findAll = (req, res) => {
    const query_campaign_id = req.query.campaign_id;
    var condition = query_campaign_id ? { campaign_id: query_campaign_id } : null;
  
    Campaign_Branch_Association.findAll({ where: condition })
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving branches."
        });
      });
  };
  

// Find a single Campaign_Branch_Association with an id
exports.findOne = (req, res) => {
    const id = req.params.id;
  
    Campaign_Branch_Association.findByPk(id)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message: "Error retrieving Campaign_Branch_Association with id=" + id
        });
      });
  };

// Update a Campaign_Branch_Association by the id in the request
exports.update = (req, res) => {
    const id = req.params.id;
  
    Campaign_Branch_Association.update(req.body, {
      where: { id: id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Campaign_Branch_Association was updated successfully."
          });
        } else {
          res.send({
            message: `Cannot update Campaign_Branch_Association with id=${id}. Maybe Campaign_Branch_Association was not found or req.body is empty!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Error updating Campaign_Branch_Association with id=" + id
        });
      });
  };

// Delete a Campaign_Branch_Association with the specified id in the request
exports.delete = (req, res) => {
    const id = req.params.id;
  
    Campaign_Branch_Association.destroy({
      where: { id: id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Campaign_Branch_Association was deleted successfully!"
          });
        } else {
          res.send({
            message: `Cannot delete Campaign_Branch_Association with id=${id}. Maybe Campaign_Branch_Association was not found!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Could not delete Campaign_Branch_Association with id=" + id
        });
      });
  };

// Delete all Branchs from the database.
exports.deleteAll = (req, res) => {
    Campaign_Branch_Association.destroy({
      where: {},
      truncate: false
    })
      .then(nums => {
        res.send({ message: `${nums} Branchs were deleted successfully!` });
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while removing all Branchs."
        });
      });
  };

