const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Campaign_Task_Association = sequelize.define("campaign_task_association", {
      campaign_task_association_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      campaign_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {     
          model: 'campaigns',
          key: 'campaign_id'
        }  
      },
      task_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {     
          model: 'tasks',
          key: 'task_id'
        }  
      },
    });
    return Campaign_Task_Association;
  };