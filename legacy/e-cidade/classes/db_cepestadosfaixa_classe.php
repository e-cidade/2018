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
//CLASSE DA ENTIDADE cepestadosfaixa
class cl_cepestadosfaixa { 
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
   var $cp04_sigla = null; 
   var $cp04_faixa = 0; 
   var $cp04_cepinicial = null; 
   var $cp04_cepfinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cp04_sigla = varchar(2) = Sigla Estado Faixa 
                 cp04_faixa = int4 = Faixa de Estados 
                 cp04_cepinicial = varchar(8) = Cep inicial 
                 cp04_cepfinal = varchar(8) = Cep final 
                 ";
   //funcao construtor da classe 
   function cl_cepestadosfaixa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cepestadosfaixa"); 
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
       $this->cp04_sigla = ($this->cp04_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["cp04_sigla"]:$this->cp04_sigla);
       $this->cp04_faixa = ($this->cp04_faixa == ""?@$GLOBALS["HTTP_POST_VARS"]["cp04_faixa"]:$this->cp04_faixa);
       $this->cp04_cepinicial = ($this->cp04_cepinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["cp04_cepinicial"]:$this->cp04_cepinicial);
       $this->cp04_cepfinal = ($this->cp04_cepfinal == ""?@$GLOBALS["HTTP_POST_VARS"]["cp04_cepfinal"]:$this->cp04_cepfinal);
     }else{
       $this->cp04_sigla = ($this->cp04_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["cp04_sigla"]:$this->cp04_sigla);
       $this->cp04_faixa = ($this->cp04_faixa == ""?@$GLOBALS["HTTP_POST_VARS"]["cp04_faixa"]:$this->cp04_faixa);
     }
   }
   // funcao para inclusao
   function incluir ($cp04_sigla,$cp04_faixa){ 
      $this->atualizacampos();
     if($this->cp04_cepinicial == null ){ 
       $this->erro_sql = " Campo Cep inicial nao Informado.";
       $this->erro_campo = "cp04_cepinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp04_cepfinal == null ){ 
       $this->erro_sql = " Campo Cep final nao Informado.";
       $this->erro_campo = "cp04_cepfinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->cp04_sigla = $cp04_sigla; 
       $this->cp04_faixa = $cp04_faixa; 
     if(($this->cp04_sigla == null) || ($this->cp04_sigla == "") ){ 
       $this->erro_sql = " Campo cp04_sigla nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->cp04_faixa == null) || ($this->cp04_faixa == "") ){ 
       $this->erro_sql = " Campo cp04_faixa nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cepestadosfaixa(
                                       cp04_sigla 
                                      ,cp04_faixa 
                                      ,cp04_cepinicial 
                                      ,cp04_cepfinal 
                       )
                values (
                                '$this->cp04_sigla' 
                               ,$this->cp04_faixa 
                               ,'$this->cp04_cepinicial' 
                               ,'$this->cp04_cepfinal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Estados Faixa  ($this->cp04_sigla."-".$this->cp04_faixa) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Estados Faixa  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Estados Faixa  ($this->cp04_sigla."-".$this->cp04_faixa) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cp04_sigla."-".$this->cp04_faixa;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cp04_sigla,$this->cp04_faixa));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7184,'$this->cp04_sigla','I')");
       $resac = db_query("insert into db_acountkey values($acount,7185,'$this->cp04_faixa','I')");
       $resac = db_query("insert into db_acount values($acount,1195,7184,'','".AddSlashes(pg_result($resaco,0,'cp04_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1195,7185,'','".AddSlashes(pg_result($resaco,0,'cp04_faixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1195,7187,'','".AddSlashes(pg_result($resaco,0,'cp04_cepinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1195,7188,'','".AddSlashes(pg_result($resaco,0,'cp04_cepfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cp04_sigla=null,$cp04_faixa=null) { 
      $this->atualizacampos();
     $sql = " update cepestadosfaixa set ";
     $virgula = "";
     if(trim($this->cp04_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp04_sigla"])){ 
       $sql  .= $virgula." cp04_sigla = '$this->cp04_sigla' ";
       $virgula = ",";
       if(trim($this->cp04_sigla) == null ){ 
         $this->erro_sql = " Campo Sigla Estado Faixa nao Informado.";
         $this->erro_campo = "cp04_sigla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp04_faixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp04_faixa"])){ 
       $sql  .= $virgula." cp04_faixa = $this->cp04_faixa ";
       $virgula = ",";
       if(trim($this->cp04_faixa) == null ){ 
         $this->erro_sql = " Campo Faixa de Estados nao Informado.";
         $this->erro_campo = "cp04_faixa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp04_cepinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp04_cepinicial"])){ 
       $sql  .= $virgula." cp04_cepinicial = '$this->cp04_cepinicial' ";
       $virgula = ",";
       if(trim($this->cp04_cepinicial) == null ){ 
         $this->erro_sql = " Campo Cep inicial nao Informado.";
         $this->erro_campo = "cp04_cepinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp04_cepfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp04_cepfinal"])){ 
       $sql  .= $virgula." cp04_cepfinal = '$this->cp04_cepfinal' ";
       $virgula = ",";
       if(trim($this->cp04_cepfinal) == null ){ 
         $this->erro_sql = " Campo Cep final nao Informado.";
         $this->erro_campo = "cp04_cepfinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cp04_sigla!=null){
       $sql .= " cp04_sigla = '$this->cp04_sigla'";
     }
     if($cp04_faixa!=null){
       $sql .= " and  cp04_faixa = $this->cp04_faixa";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cp04_sigla,$this->cp04_faixa));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7184,'$this->cp04_sigla','A')");
         $resac = db_query("insert into db_acountkey values($acount,7185,'$this->cp04_faixa','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp04_sigla"]))
           $resac = db_query("insert into db_acount values($acount,1195,7184,'".AddSlashes(pg_result($resaco,$conresaco,'cp04_sigla'))."','$this->cp04_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp04_faixa"]))
           $resac = db_query("insert into db_acount values($acount,1195,7185,'".AddSlashes(pg_result($resaco,$conresaco,'cp04_faixa'))."','$this->cp04_faixa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp04_cepinicial"]))
           $resac = db_query("insert into db_acount values($acount,1195,7187,'".AddSlashes(pg_result($resaco,$conresaco,'cp04_cepinicial'))."','$this->cp04_cepinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp04_cepfinal"]))
           $resac = db_query("insert into db_acount values($acount,1195,7188,'".AddSlashes(pg_result($resaco,$conresaco,'cp04_cepfinal'))."','$this->cp04_cepfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Estados Faixa  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cp04_sigla."-".$this->cp04_faixa;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Estados Faixa  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cp04_sigla."-".$this->cp04_faixa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cp04_sigla."-".$this->cp04_faixa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cp04_sigla=null,$cp04_faixa=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cp04_sigla,$cp04_faixa));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7184,'$cp04_sigla','E')");
         $resac = db_query("insert into db_acountkey values($acount,7185,'$cp04_faixa','E')");
         $resac = db_query("insert into db_acount values($acount,1195,7184,'','".AddSlashes(pg_result($resaco,$iresaco,'cp04_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1195,7185,'','".AddSlashes(pg_result($resaco,$iresaco,'cp04_faixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1195,7187,'','".AddSlashes(pg_result($resaco,$iresaco,'cp04_cepinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1195,7188,'','".AddSlashes(pg_result($resaco,$iresaco,'cp04_cepfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cepestadosfaixa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cp04_sigla != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cp04_sigla = '$cp04_sigla' ";
        }
        if($cp04_faixa != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cp04_faixa = $cp04_faixa ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Estados Faixa  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cp04_sigla."-".$cp04_faixa;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Estados Faixa  nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cp04_sigla."-".$cp04_faixa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cp04_sigla."-".$cp04_faixa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:cepestadosfaixa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>