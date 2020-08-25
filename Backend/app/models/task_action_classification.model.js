const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Task_Action_Classification = sequelize.define("task_action_classification", {
      task_action_classification_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      name: {
        type: Sequelize.STRING(64),
        unique:true,
        allowNull: false
      },
      description: {
        type: Sequelize.STRING(255)
      },
      task_type: {
        type: Sequelize.STRING(64),
        allowNull: false,
        }  
      
    });
    return Task_Action_Classification;
  };