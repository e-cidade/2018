<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: tfd
//CLASSE DA ENTIDADE fechamentotfdprocedimento
class cl_fechamentotfdprocedimento { 
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
   var $tf40_sequencial = 0; 
   var $tf40_tfd_fechamento = 0; 
   var $tf40_tfd_pedidotfd = 0; 
   var $tf40_cgs_und = 0; 
   var $tf40_sau_procedimento = 0; 
   var $tf40_faturamentoautomatico = 'f'; 
   var $tf40_paciente = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tf40_sequencial = int4 = Código 
                 tf40_tfd_fechamento = int4 = Fechamento BPA do TFD 
                 tf40_tfd_pedidotfd = int4 = Pedido TFD 
                 tf40_cgs_und = int4 = Cgs 
                 tf40_sau_procedimento = int4 = Procedimento 
                 tf40_faturamentoautomatico = bool = Faturou Automatico 
                 tf40_paciente = bool = Se foi paciente 
                 ";
   //funcao construtor da classe 
   function cl_fechamentotfdprocedimento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("fechamentotfdprocedimento"); 
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
       $this->tf40_sequencial = ($this->tf40_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tf40_sequencial"]:$this->tf40_sequencial);
       $this->tf40_tfd_fechamento = ($this->tf40_tfd_fechamento == ""?@$GLOBALS["HTTP_POST_VARS"]["tf40_tfd_fechamento"]:$this->tf40_tfd_fechamento);
       $this->tf40_tfd_pedidotfd = ($this->tf40_tfd_pedidotfd == ""?@$GLOBALS["HTTP_POST_VARS"]["tf40_tfd_pedidotfd"]:$this->tf40_tfd_pedidotfd);
       $this->tf40_cgs_und = ($this->tf40_cgs_und == ""?@$GLOBALS["HTTP_POST_VARS"]["tf40_cgs_und"]:$this->tf40_cgs_und);
       $this->tf40_sau_procedimento = ($this->tf40_sau_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["tf40_sau_procedimento"]:$this->tf40_sau_procedimento);
       $this->tf40_faturamentoautomatico = ($this->tf40_faturamentoautomatico == "f"?@$GLOBALS["HTTP_POST_VARS"]["tf40_faturamentoautomatico"]:$this->tf40_faturamentoautomatico);
       $this->tf40_paciente = ($this->tf40_paciente == "f"?@$GLOBALS["HTTP_POST_VARS"]["tf40_paciente"]:$this->tf40_paciente);
     }else{
       $this->tf40_sequencial = ($this->tf40_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tf40_sequencial"]:$this->tf40_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($tf40_sequencial){ 
      $this->atualizacampos();
     if($this->tf40_tfd_fechamento == null ){ 
       $this->erro_sql = " Campo Fechamento BPA do TFD não informado.";
       $this->erro_campo = "tf40_tfd_fechamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf40_tfd_pedidotfd == null ){ 
       $this->erro_sql = " Campo Pedido TFD não informado.";
       $this->erro_campo = "tf40_tfd_pedidotfd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf40_cgs_und == null ){ 
       $this->erro_sql = " Campo Cgs não informado.";
       $this->erro_campo = "tf40_cgs_und";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf40_sau_procedimento == null ){ 
       $this->erro_sql = " Campo Procedimento não informado.";
       $this->erro_campo = "tf40_sau_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf40_faturamentoautomatico == null ){ 
       $this->erro_sql = " Campo Faturou Automatico não informado.";
       $this->erro_campo = "tf40_faturamentoautomatico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf40_paciente == null ){ 
       $this->erro_sql = " Campo Se foi paciente não informado.";
       $this->erro_campo = "tf40_paciente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf40_sequencial == "" || $tf40_sequencial == null ){
       $result = db_query("select nextval('fechamentotfdprocedimento_tf40_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: fechamentotfdprocedimento_tf40_sequencial_seq do campo: tf40_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tf40_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from fechamentotfdprocedimento_tf40_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf40_sequencial)){
         $this->erro_sql = " Campo tf40_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf40_sequencial = $tf40_sequencial; 
       }
     }
     if(($this->tf40_sequencial == null) || ($this->tf40_sequencial == "") ){ 
       $this->erro_sql = " Campo tf40_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into fechamentotfdprocedimento(
                                       tf40_sequencial 
                                      ,tf40_tfd_fechamento 
                                      ,tf40_tfd_pedidotfd 
                                      ,tf40_cgs_und 
                                      ,tf40_sau_procedimento 
                                      ,tf40_faturamentoautomatico 
                                      ,tf40_paciente 
                       )
                values (
                                $this->tf40_sequencial 
                               ,$this->tf40_tfd_fechamento 
                               ,$this->tf40_tfd_pedidotfd 
                               ,$this->tf40_cgs_und 
                               ,$this->tf40_sau_procedimento 
                               ,'$this->tf40_faturamentoautomatico' 
                               ,'$this->tf40_paciente' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Fechamento Procedimento TFD ($this->tf40_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Fechamento Procedimento TFD já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Fechamento Procedimento TFD ($this->tf40_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf40_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tf40_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20303,'$this->tf40_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3651,20303,'','".AddSlashes(pg_result($resaco,0,'tf40_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3651,20304,'','".AddSlashes(pg_result($resaco,0,'tf40_tfd_fechamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3651,20305,'','".AddSlashes(pg_result($resaco,0,'tf40_tfd_pedidotfd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3651,20307,'','".AddSlashes(pg_result($resaco,0,'tf40_cgs_und'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3651,20308,'','".AddSlashes(pg_result($resaco,0,'tf40_sau_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3651,20309,'','".AddSlashes(pg_result($resaco,0,'tf40_faturamentoautomatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3651,20310,'','".AddSlashes(pg_result($resaco,0,'tf40_paciente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tf40_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update fechamentotfdprocedimento set ";
     $virgula = "";
     if(trim($this->tf40_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf40_sequencial"])){ 
       $sql  .= $virgula." tf40_sequencial = $this->tf40_sequencial ";
       $virgula = ",";
       if(trim($this->tf40_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "tf40_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf40_tfd_fechamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf40_tfd_fechamento"])){ 
       $sql  .= $virgula." tf40_tfd_fechamento = $this->tf40_tfd_fechamento ";
       $virgula = ",";
       if(trim($this->tf40_tfd_fechamento) == null ){ 
         $this->erro_sql = " Campo Fechamento BPA do TFD não informado.";
         $this->erro_campo = "tf40_tfd_fechamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf40_tfd_pedidotfd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf40_tfd_pedidotfd"])){ 
       $sql  .= $virgula." tf40_tfd_pedidotfd = $this->tf40_tfd_pedidotfd ";
       $virgula = ",";
       if(trim($this->tf40_tfd_pedidotfd) == null ){ 
         $this->erro_sql = " Campo Pedido TFD não informado.";
         $this->erro_campo = "tf40_tfd_pedidotfd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf40_cgs_und)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf40_cgs_und"])){ 
       $sql  .= $virgula." tf40_cgs_und = $this->tf40_cgs_und ";
       $virgula = ",";
       if(trim($this->tf40_cgs_und) == null ){ 
         $this->erro_sql = " Campo Cgs não informado.";
         $this->erro_campo = "tf40_cgs_und";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf40_sau_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf40_sau_procedimento"])){ 
       $sql  .= $virgula." tf40_sau_procedimento = $this->tf40_sau_procedimento ";
       $virgula = ",";
       if(trim($this->tf40_sau_procedimento) == null ){ 
         $this->erro_sql = " Campo Procedimento não informado.";
         $this->erro_campo = "tf40_sau_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf40_faturamentoautomatico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf40_faturamentoautomatico"])){ 
       $sql  .= $virgula." tf40_faturamentoautomatico = '$this->tf40_faturamentoautomatico' ";
       $virgula = ",";
       if(trim($this->tf40_faturamentoautomatico) == null ){ 
         $this->erro_sql = " Campo Faturou Automatico não informado.";
         $this->erro_campo = "tf40_faturamentoautomatico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf40_paciente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf40_paciente"])){ 
       $sql  .= $virgula." tf40_paciente = '$this->tf40_paciente' ";
       $virgula = ",";
       if(trim($this->tf40_paciente) == null ){ 
         $this->erro_sql = " Campo Se foi paciente não informado.";
         $this->erro_campo = "tf40_paciente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tf40_sequencial!=null){
       $sql .= " tf40_sequencial = $this->tf40_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tf40_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20303,'$this->tf40_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf40_sequencial"]) || $this->tf40_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3651,20303,'".AddSlashes(pg_result($resaco,$conresaco,'tf40_sequencial'))."','$this->tf40_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf40_tfd_fechamento"]) || $this->tf40_tfd_fechamento != "")
             $resac = db_query("insert into db_acount values($acount,3651,20304,'".AddSlashes(pg_result($resaco,$conresaco,'tf40_tfd_fechamento'))."','$this->tf40_tfd_fechamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf40_tfd_pedidotfd"]) || $this->tf40_tfd_pedidotfd != "")
             $resac = db_query("insert into db_acount values($acount,3651,20305,'".AddSlashes(pg_result($resaco,$conresaco,'tf40_tfd_pedidotfd'))."','$this->tf40_tfd_pedidotfd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf40_cgs_und"]) || $this->tf40_cgs_und != "")
             $resac = db_query("insert into db_acount values($acount,3651,20307,'".AddSlashes(pg_result($resaco,$conresaco,'tf40_cgs_und'))."','$this->tf40_cgs_und',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf40_sau_procedimento"]) || $this->tf40_sau_procedimento != "")
             $resac = db_query("insert into db_acount values($acount,3651,20308,'".AddSlashes(pg_result($resaco,$conresaco,'tf40_sau_procedimento'))."','$this->tf40_sau_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf40_faturamentoautomatico"]) || $this->tf40_faturamentoautomatico != "")
             $resac = db_query("insert into db_acount values($acount,3651,20309,'".AddSlashes(pg_result($resaco,$conresaco,'tf40_faturamentoautomatico'))."','$this->tf40_faturamentoautomatico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf40_paciente"]) || $this->tf40_paciente != "")
             $resac = db_query("insert into db_acount values($acount,3651,20310,'".AddSlashes(pg_result($resaco,$conresaco,'tf40_paciente'))."','$this->tf40_paciente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fechamento Procedimento TFD nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf40_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fechamento Procedimento TFD nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tf40_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($tf40_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20303,'$tf40_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3651,20303,'','".AddSlashes(pg_result($resaco,$iresaco,'tf40_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3651,20304,'','".AddSlashes(pg_result($resaco,$iresaco,'tf40_tfd_fechamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3651,20305,'','".AddSlashes(pg_result($resaco,$iresaco,'tf40_tfd_pedidotfd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3651,20307,'','".AddSlashes(pg_result($resaco,$iresaco,'tf40_cgs_und'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3651,20308,'','".AddSlashes(pg_result($resaco,$iresaco,'tf40_sau_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3651,20309,'','".AddSlashes(pg_result($resaco,$iresaco,'tf40_faturamentoautomatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3651,20310,'','".AddSlashes(pg_result($resaco,$iresaco,'tf40_paciente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from fechamentotfdprocedimento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf40_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf40_sequencial = $tf40_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fechamento Procedimento TFD nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf40_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fechamento Procedimento TFD nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf40_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:fechamentotfdprocedimento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tf40_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fechamentotfdprocedimento ";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = fechamentotfdprocedimento.tf40_sau_procedimento";
     $sql .= "      inner join tfd_pedidotfd  on  tfd_pedidotfd.tf01_i_codigo = fechamentotfdprocedimento.tf40_tfd_pedidotfd";
     $sql .= "      inner join tfd_fechamento  on  tfd_fechamento.tf32_i_codigo = fechamentotfdprocedimento.tf40_tfd_fechamento";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = fechamentotfdprocedimento.tf40_cgs_und";
     $sql .= "      inner join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento";
     $sql .= "      left  join sau_rubrica  on  sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica";
     $sql .= "      inner join sau_complexidade  on  sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_pedidotfd.tf01_i_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = tfd_pedidotfd.tf01_i_depto";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo";
     $sql .= "      inner join tfd_tipotratamento  on  tfd_tipotratamento.tf04_i_codigo = tfd_pedidotfd.tf01_i_tipotratamento";
     $sql .= "      inner join tfd_situacaotfd  on  tfd_situacaotfd.tf26_i_codigo = tfd_pedidotfd.tf01_i_situacao";
     $sql .= "      inner join tfd_tipotransporte  on  tfd_tipotransporte.tf27_i_codigo = tfd_pedidotfd.tf01_i_tipotransporte";
     $sql .= "      left  join medicos  on  medicos.sd03_i_codigo = tfd_pedidotfd.tf01_i_profissionalsolic";
     $sql .= "      inner join cgs_und  as a on   a.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund";
     $sql .= "      inner join sau_financiamento  as b on   b.sd65_i_codigo = tfd_fechamento.tf32_i_financiamento";
     $sql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      inner join cgs  as c on   c.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($tf40_sequencial!=null ){
         $sql2 .= " where fechamentotfdprocedimento.tf40_sequencial = $tf40_sequencial "; 
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
   function sql_query_file ( $tf40_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fechamentotfdprocedimento ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf40_sequencial!=null ){
         $sql2 .= " where fechamentotfdprocedimento.tf40_sequencial = $tf40_sequencial "; 
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