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

//MODULO: TFD
//CLASSE DA ENTIDADE tfd_passageiroretorno
class cl_tfd_passageiroretorno { 
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
   var $tf31_i_codigo = 0; 
   var $tf31_i_veiculodestino = 0; 
   var $tf31_i_passageiroveiculo = 0; 
   var $tf31_i_valido = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tf31_i_codigo = int4 = C�digo 
                 tf31_i_veiculodestino = int4 = Ve�culo de Retorno 
                 tf31_i_passageiroveiculo = int4 = Paciente 
                 tf31_i_valido = int4 = V�lido 
                 ";
   //funcao construtor da classe 
   function cl_tfd_passageiroretorno() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_passageiroretorno"); 
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
       $this->tf31_i_codigo = ($this->tf31_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf31_i_codigo"]:$this->tf31_i_codigo);
       $this->tf31_i_veiculodestino = ($this->tf31_i_veiculodestino == ""?@$GLOBALS["HTTP_POST_VARS"]["tf31_i_veiculodestino"]:$this->tf31_i_veiculodestino);
       $this->tf31_i_passageiroveiculo = ($this->tf31_i_passageiroveiculo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf31_i_passageiroveiculo"]:$this->tf31_i_passageiroveiculo);
       $this->tf31_i_valido = ($this->tf31_i_valido == ""?@$GLOBALS["HTTP_POST_VARS"]["tf31_i_valido"]:$this->tf31_i_valido);
     }else{
       $this->tf31_i_codigo = ($this->tf31_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf31_i_codigo"]:$this->tf31_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf31_i_codigo){ 
      $this->atualizacampos();
     if($this->tf31_i_veiculodestino == null ){ 
       $this->erro_sql = " Campo Ve�culo de Retorno nao Informado.";
       $this->erro_campo = "tf31_i_veiculodestino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf31_i_passageiroveiculo == null ){ 
       $this->erro_sql = " Campo Paciente nao Informado.";
       $this->erro_campo = "tf31_i_passageiroveiculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf31_i_valido == null ){ 
       $this->erro_sql = " Campo V�lido nao Informado.";
       $this->erro_campo = "tf31_i_valido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf31_i_codigo == "" || $tf31_i_codigo == null ){
       $result = db_query("select nextval('tfd_passageiroretorno_tf31_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_passageiroretorno_tf31_i_codigo_seq do campo: tf31_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tf31_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tfd_passageiroretorno_tf31_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf31_i_codigo)){
         $this->erro_sql = " Campo tf31_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf31_i_codigo = $tf31_i_codigo; 
       }
     }
     if(($this->tf31_i_codigo == null) || ($this->tf31_i_codigo == "") ){ 
       $this->erro_sql = " Campo tf31_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_passageiroretorno(
                                       tf31_i_codigo 
                                      ,tf31_i_veiculodestino 
                                      ,tf31_i_passageiroveiculo 
                                      ,tf31_i_valido 
                       )
                values (
                                $this->tf31_i_codigo 
                               ,$this->tf31_i_veiculodestino 
                               ,$this->tf31_i_passageiroveiculo 
                               ,$this->tf31_i_valido 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_passageiroretorno ($this->tf31_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_passageiroretorno j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_passageiroretorno ($this->tf31_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf31_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tf31_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17306,'$this->tf31_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3064,17306,'','".AddSlashes(pg_result($resaco,0,'tf31_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3064,17308,'','".AddSlashes(pg_result($resaco,0,'tf31_i_veiculodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3064,17309,'','".AddSlashes(pg_result($resaco,0,'tf31_i_passageiroveiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3064,17310,'','".AddSlashes(pg_result($resaco,0,'tf31_i_valido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tf31_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tfd_passageiroretorno set ";
     $virgula = "";
     if(trim($this->tf31_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf31_i_codigo"])){ 
       $sql  .= $virgula." tf31_i_codigo = $this->tf31_i_codigo ";
       $virgula = ",";
       if(trim($this->tf31_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "tf31_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf31_i_veiculodestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf31_i_veiculodestino"])){ 
       $sql  .= $virgula." tf31_i_veiculodestino = $this->tf31_i_veiculodestino ";
       $virgula = ",";
       if(trim($this->tf31_i_veiculodestino) == null ){ 
         $this->erro_sql = " Campo Ve�culo de Retorno nao Informado.";
         $this->erro_campo = "tf31_i_veiculodestino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf31_i_passageiroveiculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf31_i_passageiroveiculo"])){ 
       $sql  .= $virgula." tf31_i_passageiroveiculo = $this->tf31_i_passageiroveiculo ";
       $virgula = ",";
       if(trim($this->tf31_i_passageiroveiculo) == null ){ 
         $this->erro_sql = " Campo Paciente nao Informado.";
         $this->erro_campo = "tf31_i_passageiroveiculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf31_i_valido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf31_i_valido"])){ 
       $sql  .= $virgula." tf31_i_valido = $this->tf31_i_valido ";
       $virgula = ",";
       if(trim($this->tf31_i_valido) == null ){ 
         $this->erro_sql = " Campo V�lido nao Informado.";
         $this->erro_campo = "tf31_i_valido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tf31_i_codigo!=null){
       $sql .= " tf31_i_codigo = $this->tf31_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tf31_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17306,'$this->tf31_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf31_i_codigo"]) || $this->tf31_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3064,17306,'".AddSlashes(pg_result($resaco,$conresaco,'tf31_i_codigo'))."','$this->tf31_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf31_i_veiculodestino"]) || $this->tf31_i_veiculodestino != "")
           $resac = db_query("insert into db_acount values($acount,3064,17308,'".AddSlashes(pg_result($resaco,$conresaco,'tf31_i_veiculodestino'))."','$this->tf31_i_veiculodestino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf31_i_passageiroveiculo"]) || $this->tf31_i_passageiroveiculo != "")
           $resac = db_query("insert into db_acount values($acount,3064,17309,'".AddSlashes(pg_result($resaco,$conresaco,'tf31_i_passageiroveiculo'))."','$this->tf31_i_passageiroveiculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf31_i_valido"]) || $this->tf31_i_valido != "")
           $resac = db_query("insert into db_acount values($acount,3064,17310,'".AddSlashes(pg_result($resaco,$conresaco,'tf31_i_valido'))."','$this->tf31_i_valido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_passageiroretorno nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf31_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_passageiroretorno nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf31_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf31_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tf31_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tf31_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17306,'$tf31_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3064,17306,'','".AddSlashes(pg_result($resaco,$iresaco,'tf31_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3064,17308,'','".AddSlashes(pg_result($resaco,$iresaco,'tf31_i_veiculodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3064,17309,'','".AddSlashes(pg_result($resaco,$iresaco,'tf31_i_passageiroveiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3064,17310,'','".AddSlashes(pg_result($resaco,$iresaco,'tf31_i_valido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tfd_passageiroretorno
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf31_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf31_i_codigo = $tf31_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_passageiroretorno nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf31_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_passageiroretorno nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf31_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf31_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tfd_passageiroretorno";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tf31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_passageiroretorno ";
     $sql .= "      inner join tfd_veiculodestino  on  tfd_veiculodestino.tf18_i_codigo = tfd_passageiroretorno.tf31_i_veiculodestino";
     $sql .= "      inner join tfd_passageiroveiculo  on  tfd_passageiroveiculo.tf19_i_codigo = tfd_passageiroretorno.tf31_i_passageiroveiculo";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = tfd_veiculodestino.tf18_i_veiculo";
     $sql .= "      left  join veicmotoristas  on  veicmotoristas.ve05_codigo = tfd_veiculodestino.tf18_i_motorista";
     $sql .= "      inner join tfd_destino  on  tfd_destino.tf03_i_codigo = tfd_veiculodestino.tf18_i_destino";
     $sql .= "      inner join tfd_pedidotfd  on  tfd_pedidotfd.tf01_i_codigo = tfd_passageiroveiculo.tf19_i_pedidotfd";
     $sql .= "      inner join tfd_veiculodestino as a on  tfd_veiculodestino.tf18_i_codigo = tfd_passageiroveiculo.tf19_i_veiculodestino";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = tfd_passageiroveiculo.tf19_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($tf31_i_codigo!=null ){
         $sql2 .= " where tfd_passageiroretorno.tf31_i_codigo = $tf31_i_codigo "; 
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
   // funcao do sql 
   function sql_query_file ( $tf31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_passageiroretorno ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf31_i_codigo!=null ){
         $sql2 .= " where tfd_passageiroretorno.tf31_i_codigo = $tf31_i_codigo "; 
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

  function sql_query2 ( $tf31_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_passageiroretorno ";
     $sql .= "      inner join tfd_veiculodestino  on  tfd_veiculodestino.tf18_i_codigo = tfd_passageiroretorno.tf31_i_veiculodestino";
     $sql .= "      inner join tfd_passageiroveiculo  on  tfd_passageiroveiculo.tf19_i_codigo = tfd_passageiroretorno.tf31_i_passageiroveiculo";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = tfd_veiculodestino.tf18_i_veiculo";
     $sql .= "      left  join veicmotoristas  on  veicmotoristas.ve05_codigo = tfd_veiculodestino.tf18_i_motorista";
     $sql .= "      inner join tfd_destino  on  tfd_destino.tf03_i_codigo = tfd_veiculodestino.tf18_i_destino";
     $sql .= "      inner join tfd_pedidotfd  on  tfd_pedidotfd.tf01_i_codigo = tfd_passageiroveiculo.tf19_i_pedidotfd";
    // $sql .= "      inner join tfd_veiculodestino as a on  tfd_veiculodestino.tf18_i_codigo = tfd_passageiroveiculo.tf19_i_veiculodestino";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = tfd_passageiroveiculo.tf19_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($tf31_i_codigo!=null ){
         $sql2 .= " where tfd_passageiroretorno.tf31_i_codigo = $tf31_i_codigo "; 
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