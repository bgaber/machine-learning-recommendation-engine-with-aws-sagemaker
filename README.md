# Machine Learning Recommendation Engine with AWS SageMaker
![Alt text](CloudGuruChallenge.jpg?raw=true "Machine Learning Recommendation Engine with AWS SageMaker")

Goal
----
The goal of this project is to build a Netflix style recommendation engine with AWS SageMaker and other ML tools.

I am currently working on this project and artifacts should appear here by mid-November 2020.  Typically, in any Machine Learning project the Data Preparation phase takes up about 70% of the project time.

Project Description
-------------------
Using IMDb Datasets, AWS SageMaker, Jupyter hosted notebook, Python data science libraries and exploring matplotlib, scikit-learn and the k-means learning algorithm I will create a Netflix Style Recommendation Engine.

Main Steps
----------
1. Create Jupyter hosted notebook

To start the data inspection process, I will launch a Jupyter hosted notebook on Amazon SageMaker. I will use Python and various data science libraries like NumPy and Pandas’ DataFrame to work with your data.

2. Inspect and visualize data

It’s important to gain domain knowledge of the IMDb data so that I can easily detect anomalies and outliers. I will use Matplotlib for this step.

3. Prepare and transform data

The next step is to put the data in a format a machine can learn from. Transforming may include combine disjointed data files into one, remove null values, convert strings to numbers, or perform some feature engineering.

4. Train

Now that the data is prepared, the training process will begin using the selected machine learning algorithm. The algorithm should cluster or group the IMDb data in order to make recommendations. The initial plan is to use the k-means clustering algorithm.  Amazon SageMaker provides a k-means clustering algorithm or I may explore scikit-learn’s version.

5. Recommend

Now that clusters are identified, recommend the movies with Python code that analyzes the clusters to find commonalities. Once commonalities are understood, I should be able to find other movies that are similar to recommend.


