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

//MODULO: cadastro
//CLASSE DA ENTIDADE averbatipo
class cl_averbatipo { 
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
   var $j93_codigo = 0; 
   var $j93_descr = null; 
   var $j93_regra = 0; 
   var $j93_datalimite_dia = null; 
   var $j93_datalimite_mes = null; 
   var $j93_datalimite_ano = null; 
   var $j93_datalimite = null; 
   var $j93_averbagrupo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j93_codigo = int4 = Código 
                 j93_descr = varchar(20) = Descrição 
                 j93_regra = int4 = Regra 
                 j93_datalimite = date = Data limite 
                 j93_averbagrupo = int4 = Grupo 
                 ";
   //funcao construtor da classe 
   function cl_averbatipo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("averbatipo"); 
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
       $this->j93_codigo = ($this->j93_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j93_codigo"]:$this->j93_codigo);
       $this->j93_descr = ($this->j93_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["j93_descr"]:$this->j93_descr);
       $this->j93_regra = ($this->j93_regra == ""?@$GLOBALS["HTTP_POST_VARS"]["j93_regra"]:$this->j93_regra);
       if($this->j93_datalimite == ""){
         $this->j93_datalimite_dia = ($this->j93_datalimite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j93_datalimite_dia"]:$this->j93_datalimite_dia);
         $this->j93_datalimite_mes = ($this->j93_datalimite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j93_datalimite_mes"]:$this->j93_datalimite_mes);
         $this->j93_datalimite_ano = ($this->j93_datalimite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j93_datalimite_ano"]:$this->j93_datalimite_ano);
         if($this->j93_datalimite_dia != ""){
            $this->j93_datalimite = $this->j93_datalimite_ano."-".$this->j93_datalimite_mes."-".$this->j93_datalimite_dia;
         }
       }
       $this->j93_averbagrupo = ($this->j93_averbagrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["j93_averbagrupo"]:$this->j93_averbagrupo);
     }else{
       $this->j93_codigo = ($this->j93_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j93_codigo"]:$this->j93_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($j93_codigo){ 
      $this->atualizacampos();
     if($this->j93_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "j93_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j93_regra == null ){ 
       $this->erro_sql = " Campo Regra nao Informado.";
       $this->erro_campo = "j93_regra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j93_datalimite == null ){ 
       $this->j93_datalimite = "null";
     }
     if($this->j93_averbagrupo == null ){ 
       $this->erro_sql = " Campo Grupo nao Informado.";
       $this->erro_campo = "j93_averbagrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j93_codigo == "" || $j93_codigo == null ){
       $result = db_query("select nextval('averbatipo_j93_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: averbatipo_j93_codigo_seq do campo: j93_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j93_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from averbatipo_j93_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $j93_codigo)){
         $this->erro_sql = " Campo j93_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j93_codigo = $j93_codigo; 
       }
     }
     if(($this->j93_codigo == null) || ($this->j93_codigo == "") ){ 
       $this->erro_sql = " Campo j93_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into averbatipo(
                                       j93_codigo 
                                      ,j93_descr 
                                      ,j93_regra 
                                      ,j93_datalimite 
                                      ,j93_averbagrupo 
                       )
                values (
                                $this->j93_codigo 
                               ,'$this->j93_descr' 
                               ,$this->j93_regra 
                               ,".($this->j93_datalimite == "null" || $this->j93_datalimite == ""?"null":"'".$this->j93_datalimite."'")." 
                               ,$this->j93_averbagrupo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tipos de averbação ($this->j93_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tipos de averbação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tipos de averbação ($this->j93_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j93_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j93_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9677,'$this->j93_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1665,9677,'','".AddSlashes(pg_result($resaco,0,'j93_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1665,9678,'','".AddSlashes(pg_result($resaco,0,'j93_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1665,9679,'','".AddSlashes(pg_result($resaco,0,'j93_regra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1665,11690,'','".AddSlashes(pg_result($resaco,0,'j93_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1665,11737,'','".AddSlashes(pg_result($resaco,0,'j93_averbagrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j93_codigo=null) { 
      $this->atualizacampos();
     $sql = " update averbatipo set ";
     $virgula = "";
     if(trim($this->j93_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j93_codigo"])){ 
       $sql  .= $virgula." j93_codigo = $this->j93_codigo ";
       $virgula = ",";
       if(trim($this->j93_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "j93_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j93_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j93_descr"])){ 
       $sql  .= $virgula." j93_descr = '$this->j93_descr' ";
       $virgula = ",";
       if(trim($this->j93_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "j93_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j93_regra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j93_regra"])){ 
       $sql  .= $virgula." j93_regra = $this->j93_regra ";
       $virgula = ",";
       if(trim($this->j93_regra) == null ){ 
         $this->erro_sql = " Campo Regra nao Informado.";
         $this->erro_campo = "j93_regra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j93_datalimite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j93_datalimite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j93_datalimite_dia"] !="") ){ 
       $sql  .= $virgula." j93_datalimite = '$this->j93_datalimite' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j93_datalimite_dia"])){ 
         $sql  .= $virgula." j93_datalimite = null ";
         $virgula = ",";
       }
     }
     if(trim($this->j93_averbagrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j93_averbagrupo"])){ 
       $sql  .= $virgula." j93_averbagrupo = $this->j93_averbagrupo ";
       $virgula = ",";
       if(trim($this->j93_averbagrupo) == null ){ 
         $this->erro_sql = " Campo Grupo nao Informado.";
         $this->erro_campo = "j93_averbagrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j93_codigo!=null){
       $sql .= " j93_codigo = $this->j93_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j93_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9677,'$this->j93_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j93_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1665,9677,'".AddSlashes(pg_result($resaco,$conresaco,'j93_codigo'))."','$this->j93_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j93_descr"]))
           $resac = db_query("insert into db_acount values($acount,1665,9678,'".AddSlashes(pg_result($resaco,$conresaco,'j93_descr'))."','$this->j93_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j93_regra"]))
           $resac = db_query("insert into db_acount values($acount,1665,9679,'".AddSlashes(pg_result($resaco,$conresaco,'j93_regra'))."','$this->j93_regra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j93_datalimite"]))
           $resac = db_query("insert into db_acount values($acount,1665,11690,'".AddSlashes(pg_result($resaco,$conresaco,'j93_datalimite'))."','$this->j93_datalimite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j93_averbagrupo"]))
           $resac = db_query("insert into db_acount values($acount,1665,11737,'".AddSlashes(pg_result($resaco,$conresaco,'j93_averbagrupo'))."','$this->j93_averbagrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tipos de averbação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j93_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tipos de averbação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j93_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j93_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j93_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j93_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9677,'$j93_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1665,9677,'','".AddSlashes(pg_result($resaco,$iresaco,'j93_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1665,9678,'','".AddSlashes(pg_result($resaco,$iresaco,'j93_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1665,9679,'','".AddSlashes(pg_result($resaco,$iresaco,'j93_regra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1665,11690,'','".AddSlashes(pg_result($resaco,$iresaco,'j93_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1665,11737,'','".AddSlashes(pg_result($resaco,$iresaco,'j93_averbagrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from averbatipo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j93_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j93_codigo = $j93_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tipos de averbação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j93_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tipos de averbação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j93_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j93_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:averbatipo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j93_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
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
     $sql .= " from averbatipo ";
     $sql .= "      inner join averbagrupo  on  averbagrupo.j105_sequencial = averbatipo.j93_averbagrupo";
     $sql2 = "";
     if($dbwhere==""){
       if($j93_codigo!=null ){
         $sql2 .= " where averbatipo.j93_codigo = $j93_codigo "; 
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
   function sql_query_file ( $j93_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
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
     $sql .= " from averbatipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($j93_codigo!=null ){
         $sql2 .= " where averbatipo.j93_codigo = $j93_codigo "; 
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
?>