const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Campaign_Task_Action_Choices = sequelize.define("campaign_task_action_choices", {
      campaign_task_action_choice_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      choices_value: {
        type: Sequelize.STRING,
        allowNull: false,
      },
      campaign_task_action_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // User belongsTo Company 1:1
          model: 'campaign_task_actions',
          key: 'campaign_task_action_id'
        }  
      },
    });
    return Campaign_Task_Action_Choices;
  };