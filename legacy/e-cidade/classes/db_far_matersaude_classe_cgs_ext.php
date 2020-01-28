<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: Farmacia
//CLASSE DA ENTIDADE far_matersaude


class cl_far_matersaude_ext { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $fa01_i_codigo = 0; 
   var $fa01_t_obs = null; 
   var $fa01_i_codmater = 0; 
   var $fa01_i_class = 0; 
   var $fa01_c_formafarma = null; 
   var $fa01_c_nomegenerico = null; 
   var $fa01_i_medanvisa = 0; 
   var $fa01_c_tipo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa01_i_codigo = int4 = Medicamento 
                 fa01_t_obs = text = Observação 
                 fa01_i_codmater = int4 = Código Material 
                 fa01_i_class = int4 = Classificação 
                 fa01_c_formafarma = char(40) = Forma Farmacêutica 
                 fa01_c_nomegenerico = char(40) = Nome Genérico 
                 fa01_i_medanvisa = int4 = Medicamento Anvisa 
                 fa01_c_tipo = char(2) = Tipo 
                 ";
   //funcao construtor da classe 
   function cl_far_matersaude() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("far_matersaude"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->fa01_i_codigo = ($this->fa01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_codigo"]:$this->fa01_i_codigo);
       $this->fa01_t_obs = ($this->fa01_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_t_obs"]:$this->fa01_t_obs);
       $this->fa01_i_codmater = ($this->fa01_i_codmater == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_codmater"]:$this->fa01_i_codmater);
       $this->fa01_i_class = ($this->fa01_i_class == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_class"]:$this->fa01_i_class);
       $this->fa01_c_formafarma = ($this->fa01_c_formafarma == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_c_formafarma"]:$this->fa01_c_formafarma);
       $this->fa01_c_nomegenerico = ($this->fa01_c_nomegenerico == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_c_nomegenerico"]:$this->fa01_c_nomegenerico);
       $this->fa01_i_medanvisa = ($this->fa01_i_medanvisa == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_medanvisa"]:$this->fa01_i_medanvisa);
       $this->fa01_c_tipo = ($this->fa01_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_c_tipo"]:$this->fa01_c_tipo);
     }else{
       $this->fa01_i_codigo = ($this->fa01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa01_i_codigo"]:$this->fa01_i_codigo);
     }
   }

// funcao do sql 
   function sql_query ( $fa01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select distinct on (fa01_i_codmater) ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from far_matersaude ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = far_matersaude.fa01_i_codmater";
     $sql .= "      inner join far_class  on  far_class.fa05_i_codigo = far_matersaude.fa01_i_class";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql .= "      inner join far_retiradaitens  on  far_retiradaitens.fa06_i_matersaude = far_matersaude.fa01_i_codigo";     
     $sql .= "      inner join far_retirada  on  far_retirada.fa04_i_codigo = far_retiradaitens.fa06_i_retirada";
     
     $sql2 = "";
     if($dbwhere==""){
       if($fa01_i_codigo!=null ){
         $sql2 .= " where far_matersaude.fa01_i_codigo = $fa01_i_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}