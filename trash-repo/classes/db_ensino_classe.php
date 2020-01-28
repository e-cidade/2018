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

//MODULO: educa��o
//CLASSE DA ENTIDADE ensino
class cl_ensino { 
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
   var $ed10_i_codigo = 0; 
   var $ed10_i_tipoensino = 0; 
   var $ed10_i_grauensino = 0; 
   var $ed10_c_descr = null; 
   var $ed10_c_abrev = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed10_i_codigo = int8 = C�digo 
                 ed10_i_tipoensino = int8 = Modalidade de Ensino 
                 ed10_i_grauensino = int4 = Grau de Ensino 
                 ed10_c_descr = char(50) = Descri��o 
                 ed10_c_abrev = char(5) = Abreviatura 
                 ";
   //funcao construtor da classe 
   function cl_ensino() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ensino"); 
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
       $this->ed10_i_codigo = ($this->ed10_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed10_i_codigo"]:$this->ed10_i_codigo);
       $this->ed10_i_tipoensino = ($this->ed10_i_tipoensino == ""?@$GLOBALS["HTTP_POST_VARS"]["ed10_i_tipoensino"]:$this->ed10_i_tipoensino);
       $this->ed10_i_grauensino = ($this->ed10_i_grauensino == ""?@$GLOBALS["HTTP_POST_VARS"]["ed10_i_grauensino"]:$this->ed10_i_grauensino);
       $this->ed10_c_descr = ($this->ed10_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed10_c_descr"]:$this->ed10_c_descr);
       $this->ed10_c_abrev = ($this->ed10_c_abrev == ""?@$GLOBALS["HTTP_POST_VARS"]["ed10_c_abrev"]:$this->ed10_c_abrev);
     }else{
       $this->ed10_i_codigo = ($this->ed10_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed10_i_codigo"]:$this->ed10_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed10_i_codigo){ 
      $this->atualizacampos();
     if($this->ed10_i_tipoensino == null ){ 
       $this->erro_sql = " Campo Modalidade de Ensino nao Informado.";
       $this->erro_campo = "ed10_i_tipoensino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     
     if($this->ed10_c_descr == null ){ 
       $this->erro_sql = " Campo Descri��o nao Informado.";
       $this->erro_campo = "ed10_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed10_c_abrev == null ){ 
       $this->erro_sql = " Campo Abreviatura nao Informado.";
       $this->erro_campo = "ed10_c_abrev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed10_i_codigo == "" || $ed10_i_codigo == null ){
       $result = db_query("select nextval('ensino_ed10_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ensino_ed10_i_codigo_seq do campo: ed10_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed10_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ensino_ed10_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed10_i_codigo)){
         $this->erro_sql = " Campo ed10_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed10_i_codigo = $ed10_i_codigo; 
       }
     }
     if(($this->ed10_i_codigo == null) || ($this->ed10_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed10_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ensino(
                                       ed10_i_codigo 
                                      ,ed10_i_tipoensino 
                                      ,ed10_i_grauensino 
                                      ,ed10_c_descr 
                                      ,ed10_c_abrev 
                       )
                values (
                                $this->ed10_i_codigo 
                               ,$this->ed10_i_tipoensino 
                               ,$this->ed10_i_grauensino 
                               ,'$this->ed10_c_descr' 
                               ,'$this->ed10_c_abrev' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ensinos da escola ($this->ed10_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ensinos da escola j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ensinos da escola ($this->ed10_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed10_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed10_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008261,'$this->ed10_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010045,1008261,'','".AddSlashes(pg_result($resaco,0,'ed10_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010045,1008262,'','".AddSlashes(pg_result($resaco,0,'ed10_i_tipoensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010045,1008263,'','".AddSlashes(pg_result($resaco,0,'ed10_i_grauensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010045,1008264,'','".AddSlashes(pg_result($resaco,0,'ed10_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010045,1008265,'','".AddSlashes(pg_result($resaco,0,'ed10_c_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed10_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update ensino set ";
     $virgula = "";
     if(trim($this->ed10_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed10_i_codigo"])){ 
       $sql  .= $virgula." ed10_i_codigo = $this->ed10_i_codigo ";
       $virgula = ",";
       if(trim($this->ed10_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "ed10_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed10_i_tipoensino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed10_i_tipoensino"])){ 
       $sql  .= $virgula." ed10_i_tipoensino = $this->ed10_i_tipoensino ";
       $virgula = ",";
       if(trim($this->ed10_i_tipoensino) == null ){ 
         $this->erro_sql = " Campo Modalidade de Ensino nao Informado.";
         $this->erro_campo = "ed10_i_tipoensino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed10_i_grauensino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed10_i_grauensino"])){ 
       $sql  .= $virgula." ed10_i_grauensino = $this->ed10_i_grauensino ";
       $virgula = ",";
       if(trim($this->ed10_i_grauensino) == null ){ 
         $this->erro_sql = " Campo Grau de Ensino nao Informado.";
         $this->erro_campo = "ed10_i_grauensino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed10_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed10_c_descr"])){ 
       $sql  .= $virgula." ed10_c_descr = '$this->ed10_c_descr' ";
       $virgula = ",";
       if(trim($this->ed10_c_descr) == null ){ 
         $this->erro_sql = " Campo Descri��o nao Informado.";
         $this->erro_campo = "ed10_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed10_c_abrev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed10_c_abrev"])){ 
       $sql  .= $virgula." ed10_c_abrev = '$this->ed10_c_abrev' ";
       $virgula = ",";
       if(trim($this->ed10_c_abrev) == null ){ 
         $this->erro_sql = " Campo Abreviatura nao Informado.";
         $this->erro_campo = "ed10_c_abrev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed10_i_codigo!=null){
       $sql .= " ed10_i_codigo = $this->ed10_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed10_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008261,'$this->ed10_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed10_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010045,1008261,'".AddSlashes(pg_result($resaco,$conresaco,'ed10_i_codigo'))."','$this->ed10_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed10_i_tipoensino"]))
           $resac = db_query("insert into db_acount values($acount,1010045,1008262,'".AddSlashes(pg_result($resaco,$conresaco,'ed10_i_tipoensino'))."','$this->ed10_i_tipoensino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed10_i_grauensino"]))
           $resac = db_query("insert into db_acount values($acount,1010045,1008263,'".AddSlashes(pg_result($resaco,$conresaco,'ed10_i_grauensino'))."','$this->ed10_i_grauensino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed10_c_descr"]))
           $resac = db_query("insert into db_acount values($acount,1010045,1008264,'".AddSlashes(pg_result($resaco,$conresaco,'ed10_c_descr'))."','$this->ed10_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed10_c_abrev"]))
           $resac = db_query("insert into db_acount values($acount,1010045,1008265,'".AddSlashes(pg_result($resaco,$conresaco,'ed10_c_abrev'))."','$this->ed10_c_abrev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ensinos da escola nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed10_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ensinos da escola nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed10_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed10_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed10_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed10_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008261,'$ed10_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010045,1008261,'','".AddSlashes(pg_result($resaco,$iresaco,'ed10_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010045,1008262,'','".AddSlashes(pg_result($resaco,$iresaco,'ed10_i_tipoensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010045,1008263,'','".AddSlashes(pg_result($resaco,$iresaco,'ed10_i_grauensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010045,1008264,'','".AddSlashes(pg_result($resaco,$iresaco,'ed10_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010045,1008265,'','".AddSlashes(pg_result($resaco,$iresaco,'ed10_c_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ensino
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed10_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed10_i_codigo = $ed10_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ensinos da escola nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed10_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ensinos da escola nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed10_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed10_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:ensino";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed10_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ensino ";
     $sql .= "      inner join tipoensino  on  tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
     $sql2 = "";
     if($dbwhere==""){
       if($ed10_i_codigo!=null ){
         $sql2 .= " where ensino.ed10_i_codigo = $ed10_i_codigo "; 
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
   function sql_query_curso ( $ed10_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from ensino ";
     $sql .= "      inner join tipoensino  on  tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_ensino = ensino.ed10_i_codigo";
     $sql .= "      inner join cursoescola  on  cursoescola.ed71_i_curso = cursoedu.ed29_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed10_i_codigo!=null ){
         $sql2 .= " where ensino.ed10_i_codigo = $ed10_i_codigo ";
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
   function sql_query_file ( $ed10_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from ensino ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed10_i_codigo!=null ){
         $sql2 .= " where ensino.ed10_i_codigo = $ed10_i_codigo "; 
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