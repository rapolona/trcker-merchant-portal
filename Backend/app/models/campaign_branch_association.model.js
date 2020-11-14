const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Campaign_Branch_Association = sequelize.define("campaign_branch_association", {
      campaign_branch_association_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      campaign_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // User belongsTo Company 1:1
          model: 'campaigns',
          key: 'campaign_id'
        }  
      },
      branch_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // User belongsTo Company 1:1
          model: 'branches',
          key: 'branch_id'
        }  
      },
      respondent_count: {
        type: Sequelize.INTEGER
      }
    });
    return Campaign_Branch_Association;
  };