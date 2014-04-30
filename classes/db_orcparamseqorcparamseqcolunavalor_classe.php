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

//MODULO: Configuracoes
//CLASSE DA ENTIDADE orcparamseqorcparamseqcolunavalor
class cl_orcparamseqorcparamseqcolunavalor { 
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
   var $o117_sequencial = 0; 
   var $o117_orcparamseqorcparamseqcoluna = 0; 
   var $o117_linha = 0; 
   var $o117_valor = null; 
   var $o117_instit = 0; 
   var $o117_periodo = 0; 
   var $o117_anousu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o117_sequencial = int4 = Código Sequencial 
                 o117_orcparamseqorcparamseqcoluna = int4 = Coluna 
                 o117_linha = int4 = Número da linha 
                 o117_valor = varchar(100) = Valor da Coluna 
                 o117_instit = int4 = Cod. Instituição 
                 o117_periodo = int4 = Código Sequencial 
                 o117_anousu = int4 = Ano do Valor 
                 ";
   //funcao construtor da classe 
   function cl_orcparamseqorcparamseqcolunavalor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcparamseqorcparamseqcolunavalor"); 
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
       $this->o117_sequencial = ($this->o117_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o117_sequencial"]:$this->o117_sequencial);
       $this->o117_orcparamseqorcparamseqcoluna = ($this->o117_orcparamseqorcparamseqcoluna == ""?@$GLOBALS["HTTP_POST_VARS"]["o117_orcparamseqorcparamseqcoluna"]:$this->o117_orcparamseqorcparamseqcoluna);
       $this->o117_linha = ($this->o117_linha == ""?@$GLOBALS["HTTP_POST_VARS"]["o117_linha"]:$this->o117_linha);
       $this->o117_valor = ($this->o117_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o117_valor"]:$this->o117_valor);
       $this->o117_instit = ($this->o117_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o117_instit"]:$this->o117_instit);
       $this->o117_periodo = ($this->o117_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["o117_periodo"]:$this->o117_periodo);
       $this->o117_anousu = ($this->o117_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o117_anousu"]:$this->o117_anousu);
     }else{
       $this->o117_sequencial = ($this->o117_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o117_sequencial"]:$this->o117_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o117_sequencial){ 
      $this->atualizacampos();
     if($this->o117_orcparamseqorcparamseqcoluna == null ){ 
       $this->erro_sql = " Campo Coluna nao Informado.";
       $this->erro_campo = "o117_orcparamseqorcparamseqcoluna";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o117_linha == null ){ 
       $this->erro_sql = " Campo Número da linha nao Informado.";
       $this->erro_campo = "o117_linha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o117_valor == null ){ 
       $this->erro_sql = " Campo Valor da Coluna nao Informado.";
       $this->erro_campo = "o117_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o117_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "o117_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o117_periodo == null ){ 
       $this->erro_sql = " Campo Código Sequencial nao Informado.";
       $this->erro_campo = "o117_periodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o117_anousu == null ){ 
       $this->o117_anousu = "0";
     }
     if($o117_sequencial == "" || $o117_sequencial == null ){
       $result = db_query("select nextval('orcparamseqorcparamseqcolunavalor_o117_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcparamseqorcparamseqcolunavalor_o117_sequencial_seq do campo: o117_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o117_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcparamseqorcparamseqcolunavalor_o117_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o117_sequencial)){
         $this->erro_sql = " Campo o117_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o117_sequencial = $o117_sequencial; 
       }
     }
     if(($this->o117_sequencial == null) || ($this->o117_sequencial == "") ){ 
       $this->erro_sql = " Campo o117_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcparamseqorcparamseqcolunavalor(
                                       o117_sequencial 
                                      ,o117_orcparamseqorcparamseqcoluna 
                                      ,o117_linha 
                                      ,o117_valor 
                                      ,o117_instit 
                                      ,o117_periodo 
                                      ,o117_anousu 
                       )
                values (
                                $this->o117_sequencial 
                               ,$this->o117_orcparamseqorcparamseqcoluna 
                               ,$this->o117_linha 
                               ,'$this->o117_valor' 
                               ,$this->o117_instit 
                               ,$this->o117_periodo 
                               ,$this->o117_anousu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores das Colunas ($this->o117_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores das Colunas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores das Colunas ($this->o117_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o117_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o117_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14118,'$this->o117_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2483,14118,'','".AddSlashes(pg_result($resaco,0,'o117_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2483,14119,'','".AddSlashes(pg_result($resaco,0,'o117_orcparamseqorcparamseqcoluna'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2483,14120,'','".AddSlashes(pg_result($resaco,0,'o117_linha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2483,14121,'','".AddSlashes(pg_result($resaco,0,'o117_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2483,14122,'','".AddSlashes(pg_result($resaco,0,'o117_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2483,14129,'','".AddSlashes(pg_result($resaco,0,'o117_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2483,15454,'','".AddSlashes(pg_result($resaco,0,'o117_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o117_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcparamseqorcparamseqcolunavalor set ";
     $virgula = "";
     if(trim($this->o117_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o117_sequencial"])){ 
       $sql  .= $virgula." o117_sequencial = $this->o117_sequencial ";
       $virgula = ",";
       if(trim($this->o117_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o117_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o117_orcparamseqorcparamseqcoluna)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o117_orcparamseqorcparamseqcoluna"])){ 
       $sql  .= $virgula." o117_orcparamseqorcparamseqcoluna = $this->o117_orcparamseqorcparamseqcoluna ";
       $virgula = ",";
       if(trim($this->o117_orcparamseqorcparamseqcoluna) == null ){ 
         $this->erro_sql = " Campo Coluna nao Informado.";
         $this->erro_campo = "o117_orcparamseqorcparamseqcoluna";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o117_linha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o117_linha"])){ 
       $sql  .= $virgula." o117_linha = $this->o117_linha ";
       $virgula = ",";
       if(trim($this->o117_linha) == null ){ 
         $this->erro_sql = " Campo Número da linha nao Informado.";
         $this->erro_campo = "o117_linha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o117_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o117_valor"])){ 
       $sql  .= $virgula." o117_valor = '$this->o117_valor' ";
       $virgula = ",";
       if(trim($this->o117_valor) == null ){ 
         $this->erro_sql = " Campo Valor da Coluna nao Informado.";
         $this->erro_campo = "o117_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o117_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o117_instit"])){ 
       $sql  .= $virgula." o117_instit = $this->o117_instit ";
       $virgula = ",";
       if(trim($this->o117_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "o117_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o117_periodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o117_periodo"])){ 
       $sql  .= $virgula." o117_periodo = $this->o117_periodo ";
       $virgula = ",";
       if(trim($this->o117_periodo) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o117_periodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o117_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o117_anousu"])){ 
        if(trim($this->o117_anousu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o117_anousu"])){ 
           $this->o117_anousu = "0" ; 
        } 
       $sql  .= $virgula." o117_anousu = $this->o117_anousu ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o117_sequencial!=null){
       $sql .= " o117_sequencial = $this->o117_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o117_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14118,'$this->o117_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o117_sequencial"]) || $this->o117_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2483,14118,'".AddSlashes(pg_result($resaco,$conresaco,'o117_sequencial'))."','$this->o117_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o117_orcparamseqorcparamseqcoluna"]) || $this->o117_orcparamseqorcparamseqcoluna != "")
           $resac = db_query("insert into db_acount values($acount,2483,14119,'".AddSlashes(pg_result($resaco,$conresaco,'o117_orcparamseqorcparamseqcoluna'))."','$this->o117_orcparamseqorcparamseqcoluna',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o117_linha"]) || $this->o117_linha != "")
           $resac = db_query("insert into db_acount values($acount,2483,14120,'".AddSlashes(pg_result($resaco,$conresaco,'o117_linha'))."','$this->o117_linha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o117_valor"]) || $this->o117_valor != "")
           $resac = db_query("insert into db_acount values($acount,2483,14121,'".AddSlashes(pg_result($resaco,$conresaco,'o117_valor'))."','$this->o117_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o117_instit"]) || $this->o117_instit != "")
           $resac = db_query("insert into db_acount values($acount,2483,14122,'".AddSlashes(pg_result($resaco,$conresaco,'o117_instit'))."','$this->o117_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o117_periodo"]) || $this->o117_periodo != "")
           $resac = db_query("insert into db_acount values($acount,2483,14129,'".AddSlashes(pg_result($resaco,$conresaco,'o117_periodo'))."','$this->o117_periodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o117_anousu"]) || $this->o117_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2483,15454,'".AddSlashes(pg_result($resaco,$conresaco,'o117_anousu'))."','$this->o117_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores das Colunas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o117_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores das Colunas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o117_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o117_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14118,'$o117_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2483,14118,'','".AddSlashes(pg_result($resaco,$iresaco,'o117_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2483,14119,'','".AddSlashes(pg_result($resaco,$iresaco,'o117_orcparamseqorcparamseqcoluna'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2483,14120,'','".AddSlashes(pg_result($resaco,$iresaco,'o117_linha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2483,14121,'','".AddSlashes(pg_result($resaco,$iresaco,'o117_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2483,14122,'','".AddSlashes(pg_result($resaco,$iresaco,'o117_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2483,14129,'','".AddSlashes(pg_result($resaco,$iresaco,'o117_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2483,15454,'','".AddSlashes(pg_result($resaco,$iresaco,'o117_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcparamseqorcparamseqcolunavalor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o117_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o117_sequencial = $o117_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores das Colunas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o117_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores das Colunas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o117_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcparamseqorcparamseqcolunavalor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o117_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamseqorcparamseqcolunavalor ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcparamseqorcparamseqcolunavalor.o117_instit";
     $sql .= "      inner join periodo  on  periodo.o114_sequencial = orcparamseqorcparamseqcolunavalor.o117_periodo";
     $sql .= "      inner join orcparamseqorcparamseqcoluna  on  orcparamseqorcparamseqcoluna.o116_sequencial = orcparamseqorcparamseqcolunavalor.o117_orcparamseqorcparamseqcoluna";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join orcparamseq  as a on   a.o69_codparamrel = orcparamseqorcparamseqcoluna.o116_codparamrel and   a.o69_codseq = orcparamseqorcparamseqcoluna.o116_codseq";
     $sql .= "      left  join periodo  as b on   b.o114_sequencial = orcparamseqorcparamseqcoluna.o116_periodo";
     $sql .= "      inner join orcparamseqcoluna  on  orcparamseqcoluna.o115_sequencial = orcparamseqorcparamseqcoluna.o116_orcparamseqcoluna";
     $sql2 = "";
     if($dbwhere==""){
       if($o117_sequencial!=null ){
         $sql2 .= " where orcparamseqorcparamseqcolunavalor.o117_sequencial = $o117_sequencial "; 
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
   function sql_query_file ( $o117_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamseqorcparamseqcolunavalor ";
     $sql2 = "";
     if($dbwhere==""){
       if($o117_sequencial!=null ){
         $sql2 .= " where orcparamseqorcparamseqcolunavalor.o117_sequencial = $o117_sequencial "; 
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