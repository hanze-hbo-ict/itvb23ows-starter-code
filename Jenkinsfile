pipeline {
    agent {
        docker { image 'php:8.2-apache' }
    }
    
    stages {
        stage('SonarQube') {
            steps {
                script { scannerHome = tool 'SonarQube Scanner' }
                withSonarQubeEnv('Sonarqubetest') {
                    sh "${scannerHome}/bin/sonar-scanner -Dsonar.projectKey=hive-check"
                }
            }
        }
        stage('Build') {
            steps {
                echo 'Building'
            }
        }
        stage('Test') {
            steps {
                echo 'Testing'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Deploying'
            }
        }
    }
}
