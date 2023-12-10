pipeline{
    agent any
    stages {
	    stage('Build docker container') {
            steps {
        	sh 'docker-compose build'
            }
        }

	    stage('Run docker container') {
            steps {
        	sh 'docker-compose up -d'
            }
        }
    }

    post {
      always {
          sh 'docker-compose down --remove-orphans'
      }
    }
}