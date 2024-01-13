/* Requires the Docker Pipeline plugin */
pipeline {
    agent any 
    stages {
        stage('build') {
            steps {
                echo 'Hello World'
            }
        }
        stage('SonarQubeScanner'){
            steps{
                script {scannerHome = tool 'SonarQube'}
                withSonarQubeEnv('SonarQube'){
                    sh "${scannerHome}/bin/sonar-scanner -Dsonar.projectKey=[key]"
                }
            }
        }
    }
}