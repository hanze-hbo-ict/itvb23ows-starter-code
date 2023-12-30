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
                script {def scannerHome = tool 'SonarQube Scanner';}
                withSonarQubeENv('sonarqube'){
                    sh "${scannerHome}/bin/sonar-scanner"
                }
            }
        }
    }
}