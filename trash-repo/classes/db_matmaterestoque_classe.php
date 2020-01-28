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

//MODULO: material
//CLASSE DA ENTIDADE matmaterestoque
class cl_matmaterestoque { 
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
   var $m64_sequencial = 0; 
   var $m64_almox = 0; 
   var $m64_matmater = 0; 
   var $m64_estoqueminimo = 0; 
   var $m64_estoquemaximo = 0; 
   var $m64_pontopedido = 0; 
   var $m64_localizacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m64_sequencial = int8 = C�d. Sequencial 
                 m64_almox = int4 = C�d. Dep�sito 
                 m64_matmater = int8 = C�digo do material 
                 m64_estoqueminimo = float8 = Estoque Minimo 
                 m64_estoquemaximo = float8 = Estoque M�ximo 
                 m64_pontopedido = float8 = Ponto de Pedido 
                 m64_localizacao = varchar(25) = Localiza��o 
                 ";
   //funcao construtor da classe 
   function cl_matmaterestoque() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matmaterestoque"); 
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
       $this->m64_sequencial = ($this->m64_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m64_sequencial"]:$this->m64_sequencial);
       $this->m64_almox = ($this->m64_almox == ""?@$GLOBALS["HTTP_POST_VARS"]["m64_almox"]:$this->m64_almox);
       $this->m64_matmater = ($this->m64_matmater == ""?@$GLOBALS["HTTP_POST_VARS"]["m64_matmater"]:$this->m64_matmater);
       $this->m64_estoqueminimo = ($this->m64_estoqueminimo == ""?@$GLOBALS["HTTP_POST_VARS"]["m64_estoqueminimo"]:$this->m64_estoqueminimo);
       $this->m64_estoquemaximo = ($this->m64_estoquemaximo == ""?@$GLOBALS["HTTP_POST_VARS"]["m64_estoquemaximo"]:$this->m64_estoquemaximo);
       $this->m64_pontopedido = ($this->m64_pontopedido == ""?@$GLOBALS["HTTP_POST_VARS"]["m64_pontopedido"]:$this->m64_pontopedido);
       $this->m64_localizacao = ($this->m64_localizacao == ""?@$GLOBALS["HTTP_POST_VARS"]["m64_localizacao"]:$this->m64_localizacao);
     }else{
       $this->m64_sequencial = ($this->m64_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m64_sequencial"]:$this->m64_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m64_sequencial){ 
      $this->atualizacampos();
     if($this->m64_almox == null ){ 
       $this->erro_sql = " Campo C�d. Dep�sito nao Informado.";
       $this->erro_campo = "m64_almox";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m64_matmater == null ){ 
       $this->erro_sql = " Campo C�digo do material nao Informado.";
       $this->erro_campo = "m64_matmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m64_estoqueminimo == null ){ 
       $this->erro_sql = " Campo Estoque Minimo nao Informado.";
       $this->erro_campo = "m64_estoqueminimo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m64_estoquemaximo == null ){ 
       $this->erro_sql = " Campo Estoque M�ximo nao Informado.";
       $this->erro_campo = "m64_estoquemaximo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m64_pontopedido == null ){ 
       $this->erro_sql = " Campo Ponto de Pedido nao Informado.";
       $this->erro_campo = "m64_pontopedido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m64_sequencial == "" || $m64_sequencial == null ){
       $result = db_query("select nextval('matmaterestoque_m64_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matmaterestoque_m64_sequencial_seq do campo: m64_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m64_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matmaterestoque_m64_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m64_sequencial)){
         $this->erro_sql = " Campo m64_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m64_sequencial = $m64_sequencial; 
       }
     }
     if(($this->m64_sequencial == null) || ($this->m64_sequencial == "") ){ 
       $this->erro_sql = " Campo m64_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matmaterestoque(
                                       m64_sequencial 
                                      ,m64_almox 
                                      ,m64_matmater 
                                      ,m64_estoqueminimo 
                                      ,m64_estoquemaximo 
                                      ,m64_pontopedido 
                                      ,m64_localizacao 
                       )
                values (
                                $this->m64_sequencial 
                               ,$this->m64_almox 
                               ,$this->m64_matmater 
                               ,$this->m64_estoqueminimo 
                               ,$this->m64_estoquemaximo 
                               ,$this->m64_pontopedido 
                               ,'$this->m64_localizacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Materiais em Estoque ($this->m64_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Materiais em Estoque j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Materiais em Estoque ($this->m64_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m64_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m64_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10484,'$this->m64_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1812,10484,'','".AddSlashes(pg_result($resaco,0,'m64_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1812,10485,'','".AddSlashes(pg_result($resaco,0,'m64_almox'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1812,10486,'','".AddSlashes(pg_result($resaco,0,'m64_matmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1812,10487,'','".AddSlashes(pg_result($resaco,0,'m64_estoqueminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1812,10488,'','".AddSlashes(pg_result($resaco,0,'m64_estoquemaximo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1812,10489,'','".AddSlashes(pg_result($resaco,0,'m64_pontopedido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1812,12092,'','".AddSlashes(pg_result($resaco,0,'m64_localizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m64_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update matmaterestoque set ";
     $virgula = "";
     if(trim($this->m64_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m64_sequencial"])){ 
       $sql  .= $virgula." m64_sequencial = $this->m64_sequencial ";
       $virgula = ",";
       if(trim($this->m64_sequencial) == null ){ 
         $this->erro_sql = " Campo C�d. Sequencial nao Informado.";
         $this->erro_campo = "m64_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m64_almox)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m64_almox"])){ 
       $sql  .= $virgula." m64_almox = $this->m64_almox ";
       $virgula = ",";
       if(trim($this->m64_almox) == null ){ 
         $this->erro_sql = " Campo C�d. Dep�sito nao Informado.";
         $this->erro_campo = "m64_almox";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m64_matmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m64_matmater"])){ 
       $sql  .= $virgula." m64_matmater = $this->m64_matmater ";
       $virgula = ",";
       if(trim($this->m64_matmater) == null ){ 
         $this->erro_sql = " Campo C�digo do material nao Informado.";
         $this->erro_campo = "m64_matmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m64_estoqueminimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m64_estoqueminimo"])){ 
       $sql  .= $virgula." m64_estoqueminimo = $this->m64_estoqueminimo ";
       $virgula = ",";
       if(trim($this->m64_estoqueminimo) == null ){ 
         $this->erro_sql = " Campo Estoque Minimo nao Informado.";
         $this->erro_campo = "m64_estoqueminimo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m64_estoquemaximo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m64_estoquemaximo"])){ 
       $sql  .= $virgula." m64_estoquemaximo = $this->m64_estoquemaximo ";
       $virgula = ",";
       if(trim($this->m64_estoquemaximo) == null ){ 
         $this->erro_sql = " Campo Estoque M�ximo nao Informado.";
         $this->erro_campo = "m64_estoquemaximo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m64_pontopedido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m64_pontopedido"])){ 
       $sql  .= $virgula." m64_pontopedido = $this->m64_pontopedido ";
       $virgula = ",";
       if(trim($this->m64_pontopedido) == null ){ 
         $this->erro_sql = " Campo Ponto de Pedido nao Informado.";
         $this->erro_campo = "m64_pontopedido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m64_localizacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m64_localizacao"])){ 
       $sql  .= $virgula." m64_localizacao = '$this->m64_localizacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($m64_sequencial!=null){
       $sql .= " m64_sequencial = $this->m64_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m64_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10484,'$this->m64_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m64_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1812,10484,'".AddSlashes(pg_result($resaco,$conresaco,'m64_sequencial'))."','$this->m64_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m64_almox"]))
           $resac = db_query("insert into db_acount values($acount,1812,10485,'".AddSlashes(pg_result($resaco,$conresaco,'m64_almox'))."','$this->m64_almox',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m64_matmater"]))
           $resac = db_query("insert into db_acount values($acount,1812,10486,'".AddSlashes(pg_result($resaco,$conresaco,'m64_matmater'))."','$this->m64_matmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m64_estoqueminimo"]))
           $resac = db_query("insert into db_acount values($acount,1812,10487,'".AddSlashes(pg_result($resaco,$conresaco,'m64_estoqueminimo'))."','$this->m64_estoqueminimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m64_estoquemaximo"]))
           $resac = db_query("insert into db_acount values($acount,1812,10488,'".AddSlashes(pg_result($resaco,$conresaco,'m64_estoquemaximo'))."','$this->m64_estoquemaximo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m64_pontopedido"]))
           $resac = db_query("insert into db_acount values($acount,1812,10489,'".AddSlashes(pg_result($resaco,$conresaco,'m64_pontopedido'))."','$this->m64_pontopedido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m64_localizacao"]))
           $resac = db_query("insert into db_acount values($acount,1812,12092,'".AddSlashes(pg_result($resaco,$conresaco,'m64_localizacao'))."','$this->m64_localizacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Materiais em Estoque nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m64_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Materiais em Estoque nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m64_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m64_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m64_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m64_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10484,'$m64_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1812,10484,'','".AddSlashes(pg_result($resaco,$iresaco,'m64_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1812,10485,'','".AddSlashes(pg_result($resaco,$iresaco,'m64_almox'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1812,10486,'','".AddSlashes(pg_result($resaco,$iresaco,'m64_matmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1812,10487,'','".AddSlashes(pg_result($resaco,$iresaco,'m64_estoqueminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1812,10488,'','".AddSlashes(pg_result($resaco,$iresaco,'m64_estoquemaximo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1812,10489,'','".AddSlashes(pg_result($resaco,$iresaco,'m64_pontopedido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1812,12092,'','".AddSlashes(pg_result($resaco,$iresaco,'m64_localizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matmaterestoque
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m64_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m64_sequencial = $m64_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Materiais em Estoque nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m64_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Materiais em Estoque nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m64_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m64_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matmaterestoque";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m64_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matmaterestoque ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matmaterestoque.m64_matmater";
     $sql .= "      inner join db_almox  on  db_almox.m91_codigo = matmaterestoque.m64_almox";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = db_almox.m91_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($m64_sequencial!=null ){
         $sql2 .= " where matmaterestoque.m64_sequencial = $m64_sequencial "; 
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
   function sql_query_file ( $m64_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matmaterestoque ";
     $sql2 = "";
     if($dbwhere==""){
       if($m64_sequencial!=null ){
         $sql2 .= " where matmaterestoque.m64_sequencial = $m64_sequencial "; 
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