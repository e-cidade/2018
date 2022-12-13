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

//MODULO: Merenda
//CLASSE DA ENTIDADE mer_consumoescola
class cl_mer_consumoescola { 
   // cria variaveis de erro 
   var $rotulo          = null; 
   var $query_sql       = null; 
   var $numrows         = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status     = null; 
   var $erro_sql        = null; 
   var $erro_banco      = null;  
   var $erro_msg        = null;  
   var $erro_campo      = null;  
   var $pagina_retorno  = null; 
   // cria variaveis do arquivo 
   var $me38_i_codigo        = 0; 
   var $me38_i_cardapioescola        = 0; 
   var $me38_i_tipocardapio        = 0; 
   var $me38_i_ordem        = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me38_i_codigo = int4 = C�digo 
                 me38_i_cardapioescola = int4 = Card�pio Escola 
                 me38_i_tipocardapio = int4 = Tipo Card�pio 
                 me38_i_ordem = int4 = Ordem 
                 ";
   //funcao construtor da classe 
   function cl_mer_consumoescola() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_consumoescola"); 
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
       $this->me38_i_codigo = ($this->me38_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me38_i_codigo"]:$this->me38_i_codigo);
       $this->me38_i_cardapioescola = ($this->me38_i_cardapioescola == ""?@$GLOBALS["HTTP_POST_VARS"]["me38_i_cardapioescola"]:$this->me38_i_cardapioescola);
       $this->me38_i_tipocardapio = ($this->me38_i_tipocardapio == ""?@$GLOBALS["HTTP_POST_VARS"]["me38_i_tipocardapio"]:$this->me38_i_tipocardapio);
       $this->me38_i_ordem = ($this->me38_i_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["me38_i_ordem"]:$this->me38_i_ordem);
     }else{
       $this->me38_i_codigo = ($this->me38_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me38_i_codigo"]:$this->me38_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me38_i_codigo){ 
      $this->atualizacampos();
     if($this->me38_i_cardapioescola == null ){ 
       $this->erro_sql = " Campo Card�pio Escola nao Informado.";
       $this->erro_campo = "me38_i_cardapioescola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me38_i_tipocardapio == null ){ 
       $this->erro_sql = " Campo Tipo Card�pio nao Informado.";
       $this->erro_campo = "me38_i_tipocardapio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me38_i_ordem == null ){ 
       $this->erro_sql = " Campo Ordem nao Informado.";
       $this->erro_campo = "me38_i_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me38_i_codigo == "" || $me38_i_codigo == null ){
       $result = db_query("select nextval('mer_consumoescola_me38_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mer_consumoescola_me38_i_codigo_seq do campo: me38_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me38_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mer_consumoescola_me38_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me38_i_codigo)){
         $this->erro_sql = " Campo me38_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me38_i_codigo = $me38_i_codigo; 
       }
     }
     if(($this->me38_i_codigo == null) || ($this->me38_i_codigo == "") ){ 
       $this->erro_sql = " Campo me38_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_consumoescola(
                                       me38_i_codigo 
                                      ,me38_i_cardapioescola 
                                      ,me38_i_tipocardapio 
                                      ,me38_i_ordem 
                       )
                values (
                                $this->me38_i_codigo 
                               ,$this->me38_i_cardapioescola 
                               ,$this->me38_i_tipocardapio 
                               ,$this->me38_i_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_consumoescola ($this->me38_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_consumoescola j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_consumoescola ($this->me38_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me38_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me38_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17371,'$this->me38_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3074,17371,'','".AddSlashes(pg_result($resaco,0,'me38_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3074,17372,'','".AddSlashes(pg_result($resaco,0,'me38_i_cardapioescola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3074,17373,'','".AddSlashes(pg_result($resaco,0,'me38_i_tipocardapio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3074,17374,'','".AddSlashes(pg_result($resaco,0,'me38_i_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me38_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_consumoescola set ";
     $virgula = "";
     if(trim($this->me38_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me38_i_codigo"])){ 
       $sql  .= $virgula." me38_i_codigo = $this->me38_i_codigo ";
       $virgula = ",";
       if(trim($this->me38_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "me38_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me38_i_cardapioescola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me38_i_cardapioescola"])){ 
       $sql  .= $virgula." me38_i_cardapioescola = $this->me38_i_cardapioescola ";
       $virgula = ",";
       if(trim($this->me38_i_cardapioescola) == null ){ 
         $this->erro_sql = " Campo Card�pio Escola nao Informado.";
         $this->erro_campo = "me38_i_cardapioescola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me38_i_tipocardapio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me38_i_tipocardapio"])){ 
       $sql  .= $virgula." me38_i_tipocardapio = $this->me38_i_tipocardapio ";
       $virgula = ",";
       if(trim($this->me38_i_tipocardapio) == null ){ 
         $this->erro_sql = " Campo Tipo Card�pio nao Informado.";
         $this->erro_campo = "me38_i_tipocardapio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me38_i_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me38_i_ordem"])){ 
       $sql  .= $virgula." me38_i_ordem = $this->me38_i_ordem ";
       $virgula = ",";
       if(trim($this->me38_i_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "me38_i_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me38_i_codigo!=null){
       $sql .= " me38_i_codigo = $this->me38_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me38_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17371,'$this->me38_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me38_i_codigo"]) || $this->me38_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3074,17371,'".AddSlashes(pg_result($resaco,$conresaco,'me38_i_codigo'))."','$this->me38_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me38_i_cardapioescola"]) || $this->me38_i_cardapioescola != "")
           $resac = db_query("insert into db_acount values($acount,3074,17372,'".AddSlashes(pg_result($resaco,$conresaco,'me38_i_cardapioescola'))."','$this->me38_i_cardapioescola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me38_i_tipocardapio"]) || $this->me38_i_tipocardapio != "")
           $resac = db_query("insert into db_acount values($acount,3074,17373,'".AddSlashes(pg_result($resaco,$conresaco,'me38_i_tipocardapio'))."','$this->me38_i_tipocardapio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me38_i_ordem"]) || $this->me38_i_ordem != "")
           $resac = db_query("insert into db_acount values($acount,3074,17374,'".AddSlashes(pg_result($resaco,$conresaco,'me38_i_ordem'))."','$this->me38_i_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_consumoescola nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me38_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_consumoescola nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me38_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me38_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me38_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me38_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17371,'$me38_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3074,17371,'','".AddSlashes(pg_result($resaco,$iresaco,'me38_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3074,17372,'','".AddSlashes(pg_result($resaco,$iresaco,'me38_i_cardapioescola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3074,17373,'','".AddSlashes(pg_result($resaco,$iresaco,'me38_i_tipocardapio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3074,17374,'','".AddSlashes(pg_result($resaco,$iresaco,'me38_i_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_consumoescola
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me38_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me38_i_codigo = $me38_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_consumoescola nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me38_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_consumoescola nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me38_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me38_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_consumoescola";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me38_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_consumoescola ";
     $sql .= "      inner join mer_tipocardapio  on  mer_tipocardapio.me27_i_codigo = mer_consumoescola.me38_i_tipocardapio";
     $sql .= "      inner join mer_cardapioescola  on  mer_cardapioescola.me32_i_codigo = mer_consumoescola.me38_i_cardapioescola";
     $sql .= "      inner join mer_tipocardapio   as tipocardapio on  tipocardapio.me27_i_codigo = mer_cardapioescola.me32_i_tipocardapio";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = mer_cardapioescola.me32_i_escola";
     $sql2 = "";
     if($dbwhere==""){
       if($me38_i_codigo!=null ){
         $sql2 .= " where mer_consumoescola.me38_i_codigo = $me38_i_codigo "; 
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
   function sql_query_file ( $me38_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_consumoescola ";
     $sql2 = "";
     if($dbwhere==""){
       if($me38_i_codigo!=null ){
         $sql2 .= " where mer_consumoescola.me38_i_codigo = $me38_i_codigo "; 
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