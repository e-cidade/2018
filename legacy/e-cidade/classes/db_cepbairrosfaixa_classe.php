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

//MODULO: protocolo
//CLASSE DA ENTIDADE cepbairrosfaixa
class cl_cepbairrosfaixa { 
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
   var $cp02_codbairro = 0; 
   var $cp02_faixa = 0; 
   var $cp02_cepinicial = null; 
   var $cp02_cepfinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cp02_codbairro = int8 = Codigo do Bairro 
                 cp02_faixa = int4 = Faixa de Ceps por Bairros 
                 cp02_cepinicial = varchar(8) = Cep inicial 
                 cp02_cepfinal = varchar(8) = Cep final 
                 ";
   //funcao construtor da classe 
   function cl_cepbairrosfaixa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cepbairrosfaixa"); 
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
       $this->cp02_codbairro = ($this->cp02_codbairro == ""?@$GLOBALS["HTTP_POST_VARS"]["cp02_codbairro"]:$this->cp02_codbairro);
       $this->cp02_faixa = ($this->cp02_faixa == ""?@$GLOBALS["HTTP_POST_VARS"]["cp02_faixa"]:$this->cp02_faixa);
       $this->cp02_cepinicial = ($this->cp02_cepinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["cp02_cepinicial"]:$this->cp02_cepinicial);
       $this->cp02_cepfinal = ($this->cp02_cepfinal == ""?@$GLOBALS["HTTP_POST_VARS"]["cp02_cepfinal"]:$this->cp02_cepfinal);
     }else{
       $this->cp02_codbairro = ($this->cp02_codbairro == ""?@$GLOBALS["HTTP_POST_VARS"]["cp02_codbairro"]:$this->cp02_codbairro);
       $this->cp02_faixa = ($this->cp02_faixa == ""?@$GLOBALS["HTTP_POST_VARS"]["cp02_faixa"]:$this->cp02_faixa);
     }
   }
   // funcao para inclusao
   function incluir ($cp02_codbairro,$cp02_faixa){ 
      $this->atualizacampos();
     if($this->cp02_cepinicial == null ){ 
       $this->erro_sql = " Campo Cep inicial nao Informado.";
       $this->erro_campo = "cp02_cepinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp02_cepfinal == null ){ 
       $this->erro_sql = " Campo Cep final nao Informado.";
       $this->erro_campo = "cp02_cepfinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->cp02_codbairro = $cp02_codbairro; 
       $this->cp02_faixa = $cp02_faixa; 
     if(($this->cp02_codbairro == null) || ($this->cp02_codbairro == "") ){ 
       $this->erro_sql = " Campo cp02_codbairro nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->cp02_faixa == null) || ($this->cp02_faixa == "") ){ 
       $this->erro_sql = " Campo cp02_faixa nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cepbairrosfaixa(
                                       cp02_codbairro 
                                      ,cp02_faixa 
                                      ,cp02_cepinicial 
                                      ,cp02_cepfinal 
                       )
                values (
                                $this->cp02_codbairro 
                               ,$this->cp02_faixa 
                               ,'$this->cp02_cepinicial' 
                               ,'$this->cp02_cepfinal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Bairros Faixa ($this->cp02_codbairro."-".$this->cp02_faixa) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Bairros Faixa j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Bairros Faixa ($this->cp02_codbairro."-".$this->cp02_faixa) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cp02_codbairro."-".$this->cp02_faixa;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cp02_codbairro,$this->cp02_faixa));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7177,'$this->cp02_codbairro','I')");
       $resac = db_query("insert into db_acountkey values($acount,7178,'$this->cp02_faixa','I')");
       $resac = db_query("insert into db_acount values($acount,1193,7177,'','".AddSlashes(pg_result($resaco,0,'cp02_codbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1193,7178,'','".AddSlashes(pg_result($resaco,0,'cp02_faixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1193,7179,'','".AddSlashes(pg_result($resaco,0,'cp02_cepinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1193,7180,'','".AddSlashes(pg_result($resaco,0,'cp02_cepfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cp02_codbairro=null,$cp02_faixa=null) { 
      $this->atualizacampos();
     $sql = " update cepbairrosfaixa set ";
     $virgula = "";
     if(trim($this->cp02_codbairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp02_codbairro"])){ 
       $sql  .= $virgula." cp02_codbairro = $this->cp02_codbairro ";
       $virgula = ",";
       if(trim($this->cp02_codbairro) == null ){ 
         $this->erro_sql = " Campo Codigo do Bairro nao Informado.";
         $this->erro_campo = "cp02_codbairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp02_faixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp02_faixa"])){ 
       $sql  .= $virgula." cp02_faixa = $this->cp02_faixa ";
       $virgula = ",";
       if(trim($this->cp02_faixa) == null ){ 
         $this->erro_sql = " Campo Faixa de Ceps por Bairros nao Informado.";
         $this->erro_campo = "cp02_faixa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp02_cepinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp02_cepinicial"])){ 
       $sql  .= $virgula." cp02_cepinicial = '$this->cp02_cepinicial' ";
       $virgula = ",";
       if(trim($this->cp02_cepinicial) == null ){ 
         $this->erro_sql = " Campo Cep inicial nao Informado.";
         $this->erro_campo = "cp02_cepinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp02_cepfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp02_cepfinal"])){ 
       $sql  .= $virgula." cp02_cepfinal = '$this->cp02_cepfinal' ";
       $virgula = ",";
       if(trim($this->cp02_cepfinal) == null ){ 
         $this->erro_sql = " Campo Cep final nao Informado.";
         $this->erro_campo = "cp02_cepfinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cp02_codbairro!=null){
       $sql .= " cp02_codbairro = $this->cp02_codbairro";
     }
     if($cp02_faixa!=null){
       $sql .= " and  cp02_faixa = $this->cp02_faixa";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cp02_codbairro,$this->cp02_faixa));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7177,'$this->cp02_codbairro','A')");
         $resac = db_query("insert into db_acountkey values($acount,7178,'$this->cp02_faixa','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp02_codbairro"]))
           $resac = db_query("insert into db_acount values($acount,1193,7177,'".AddSlashes(pg_result($resaco,$conresaco,'cp02_codbairro'))."','$this->cp02_codbairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp02_faixa"]))
           $resac = db_query("insert into db_acount values($acount,1193,7178,'".AddSlashes(pg_result($resaco,$conresaco,'cp02_faixa'))."','$this->cp02_faixa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp02_cepinicial"]))
           $resac = db_query("insert into db_acount values($acount,1193,7179,'".AddSlashes(pg_result($resaco,$conresaco,'cp02_cepinicial'))."','$this->cp02_cepinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp02_cepfinal"]))
           $resac = db_query("insert into db_acount values($acount,1193,7180,'".AddSlashes(pg_result($resaco,$conresaco,'cp02_cepfinal'))."','$this->cp02_cepfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Bairros Faixa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cp02_codbairro."-".$this->cp02_faixa;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Bairros Faixa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cp02_codbairro."-".$this->cp02_faixa;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cp02_codbairro."-".$this->cp02_faixa;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cp02_codbairro=null,$cp02_faixa=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cp02_codbairro,$cp02_faixa));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7177,'$cp02_codbairro','E')");
         $resac = db_query("insert into db_acountkey values($acount,7178,'$cp02_faixa','E')");
         $resac = db_query("insert into db_acount values($acount,1193,7177,'','".AddSlashes(pg_result($resaco,$iresaco,'cp02_codbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1193,7178,'','".AddSlashes(pg_result($resaco,$iresaco,'cp02_faixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1193,7179,'','".AddSlashes(pg_result($resaco,$iresaco,'cp02_cepinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1193,7180,'','".AddSlashes(pg_result($resaco,$iresaco,'cp02_cepfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cepbairrosfaixa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cp02_codbairro != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cp02_codbairro = $cp02_codbairro ";
        }
        if($cp02_faixa != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cp02_faixa = $cp02_faixa ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Bairros Faixa nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cp02_codbairro."-".$cp02_faixa;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Bairros Faixa nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cp02_codbairro."-".$cp02_faixa;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cp02_codbairro."-".$cp02_faixa;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:cepbairrosfaixa";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>