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

//MODULO: caixa
//CLASSE DA ENTIDADE extrato
class cl_extrato { 
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
   var $k85_sequencial = 0; 
   var $k85_codbco = 0; 
   var $k85_dtproc_dia = null; 
   var $k85_dtproc_mes = null; 
   var $k85_dtproc_ano = null; 
   var $k85_dtproc = null; 
   var $k85_dtarq_dia = null; 
   var $k85_dtarq_mes = null; 
   var $k85_dtarq_ano = null; 
   var $k85_dtarq = null; 
   var $k85_convenio = null; 
   var $k85_seqarq = 0; 
   var $k85_nomearq = null; 
   var $k85_tipoinclusao = 0; 
   var $k85_conteudo = null; 
   var $k85_cnpj = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k85_sequencial = int4 = Codigo sequencial 
                 k85_codbco = int4 = codigo do banco 
                 k85_dtproc = date = Data do processamento 
                 k85_dtarq = date = Data do arquivo 
                 k85_convenio = varchar(20) = Convenio 
                 k85_seqarq = int4 = Sequencial do arquivo 
                 k85_nomearq = varchar(255) = Nome do arquivo 
                 k85_tipoinclusao = int4 = Tipo de inclusão 
                 k85_conteudo = text = Conteudo do arquivo 
                 k85_cnpj = char(14) = CNPJ 
                 ";
   //funcao construtor da classe 
   function cl_extrato() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("extrato"); 
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
       $this->k85_sequencial = ($this->k85_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k85_sequencial"]:$this->k85_sequencial);
       $this->k85_codbco = ($this->k85_codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k85_codbco"]:$this->k85_codbco);
       if($this->k85_dtproc == ""){
         $this->k85_dtproc_dia = ($this->k85_dtproc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k85_dtproc_dia"]:$this->k85_dtproc_dia);
         $this->k85_dtproc_mes = ($this->k85_dtproc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k85_dtproc_mes"]:$this->k85_dtproc_mes);
         $this->k85_dtproc_ano = ($this->k85_dtproc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k85_dtproc_ano"]:$this->k85_dtproc_ano);
         if($this->k85_dtproc_dia != ""){
            $this->k85_dtproc = $this->k85_dtproc_ano."-".$this->k85_dtproc_mes."-".$this->k85_dtproc_dia;
         }
       }
       if($this->k85_dtarq == ""){
         $this->k85_dtarq_dia = ($this->k85_dtarq_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k85_dtarq_dia"]:$this->k85_dtarq_dia);
         $this->k85_dtarq_mes = ($this->k85_dtarq_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k85_dtarq_mes"]:$this->k85_dtarq_mes);
         $this->k85_dtarq_ano = ($this->k85_dtarq_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k85_dtarq_ano"]:$this->k85_dtarq_ano);
         if($this->k85_dtarq_dia != ""){
            $this->k85_dtarq = $this->k85_dtarq_ano."-".$this->k85_dtarq_mes."-".$this->k85_dtarq_dia;
         }
       }
       $this->k85_convenio = ($this->k85_convenio == ""?@$GLOBALS["HTTP_POST_VARS"]["k85_convenio"]:$this->k85_convenio);
       $this->k85_seqarq = ($this->k85_seqarq == ""?@$GLOBALS["HTTP_POST_VARS"]["k85_seqarq"]:$this->k85_seqarq);
       $this->k85_nomearq = ($this->k85_nomearq == ""?@$GLOBALS["HTTP_POST_VARS"]["k85_nomearq"]:$this->k85_nomearq);
       $this->k85_tipoinclusao = ($this->k85_tipoinclusao == ""?@$GLOBALS["HTTP_POST_VARS"]["k85_tipoinclusao"]:$this->k85_tipoinclusao);
       $this->k85_conteudo = ($this->k85_conteudo == ""?@$GLOBALS["HTTP_POST_VARS"]["k85_conteudo"]:$this->k85_conteudo);
       $this->k85_cnpj = ($this->k85_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["k85_cnpj"]:$this->k85_cnpj);
     }else{
       $this->k85_sequencial = ($this->k85_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k85_sequencial"]:$this->k85_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k85_sequencial){ 
      $this->atualizacampos();
     if($this->k85_codbco == null ){ 
       $this->erro_sql = " Campo codigo do banco nao Informado.";
       $this->erro_campo = "k85_codbco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k85_dtproc == null ){ 
       $this->erro_sql = " Campo Data do processamento nao Informado.";
       $this->erro_campo = "k85_dtproc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k85_dtarq == null ){ 
       $this->erro_sql = " Campo Data do arquivo nao Informado.";
       $this->erro_campo = "k85_dtarq_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k85_convenio == null ){ 
       $this->erro_sql = " Campo Convenio nao Informado.";
       $this->erro_campo = "k85_convenio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k85_seqarq == null ){ 
       $this->erro_sql = " Campo Sequencial do arquivo nao Informado.";
       $this->erro_campo = "k85_seqarq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k85_nomearq == null ){ 
       $this->erro_sql = " Campo Nome do arquivo nao Informado.";
       $this->erro_campo = "k85_nomearq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k85_tipoinclusao == null ){ 
       $this->erro_sql = " Campo Tipo de inclusão nao Informado.";
       $this->erro_campo = "k85_tipoinclusao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k85_conteudo == null ){ 
       $this->erro_sql = " Campo Conteudo do arquivo nao Informado.";
       $this->erro_campo = "k85_conteudo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k85_cnpj == null ){ 
       $this->erro_sql = " Campo CNPJ nao Informado.";
       $this->erro_campo = "k85_cnpj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k85_sequencial == "" || $k85_sequencial == null ){
       $result = db_query("select nextval('extrato_k85_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: extrato_k85_sequencial_seq do campo: k85_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k85_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from extrato_k85_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k85_sequencial)){
         $this->erro_sql = " Campo k85_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k85_sequencial = $k85_sequencial; 
       }
     }
     if(($this->k85_sequencial == null) || ($this->k85_sequencial == "") ){ 
       $this->erro_sql = " Campo k85_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into extrato(
                                       k85_sequencial 
                                      ,k85_codbco 
                                      ,k85_dtproc 
                                      ,k85_dtarq 
                                      ,k85_convenio 
                                      ,k85_seqarq 
                                      ,k85_nomearq 
                                      ,k85_tipoinclusao 
                                      ,k85_conteudo 
                                      ,k85_cnpj 
                       )
                values (
                                $this->k85_sequencial 
                               ,$this->k85_codbco 
                               ,".($this->k85_dtproc == "null" || $this->k85_dtproc == ""?"null":"'".$this->k85_dtproc."'")." 
                               ,".($this->k85_dtarq == "null" || $this->k85_dtarq == ""?"null":"'".$this->k85_dtarq."'")." 
                               ,'$this->k85_convenio' 
                               ,$this->k85_seqarq 
                               ,'$this->k85_nomearq' 
                               ,$this->k85_tipoinclusao 
                               ,'$this->k85_conteudo' 
                               ,'$this->k85_cnpj' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Extrato bancario ($this->k85_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Extrato bancario já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Extrato bancario ($this->k85_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k85_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k85_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10051,'$this->k85_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1728,10051,'','".AddSlashes(pg_result($resaco,0,'k85_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1728,10052,'','".AddSlashes(pg_result($resaco,0,'k85_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1728,10053,'','".AddSlashes(pg_result($resaco,0,'k85_dtproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1728,10054,'','".AddSlashes(pg_result($resaco,0,'k85_dtarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1728,10055,'','".AddSlashes(pg_result($resaco,0,'k85_convenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1728,10056,'','".AddSlashes(pg_result($resaco,0,'k85_seqarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1728,10057,'','".AddSlashes(pg_result($resaco,0,'k85_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1728,10140,'','".AddSlashes(pg_result($resaco,0,'k85_tipoinclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1728,10058,'','".AddSlashes(pg_result($resaco,0,'k85_conteudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1728,17939,'','".AddSlashes(pg_result($resaco,0,'k85_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k85_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update extrato set ";
     $virgula = "";
     if(trim($this->k85_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k85_sequencial"])){ 
       $sql  .= $virgula." k85_sequencial = $this->k85_sequencial ";
       $virgula = ",";
       if(trim($this->k85_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "k85_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k85_codbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k85_codbco"])){ 
       $sql  .= $virgula." k85_codbco = $this->k85_codbco ";
       $virgula = ",";
       if(trim($this->k85_codbco) == null ){ 
         $this->erro_sql = " Campo codigo do banco nao Informado.";
         $this->erro_campo = "k85_codbco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k85_dtproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k85_dtproc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k85_dtproc_dia"] !="") ){ 
       $sql  .= $virgula." k85_dtproc = '$this->k85_dtproc' ";
       $virgula = ",";
       if(trim($this->k85_dtproc) == null ){ 
         $this->erro_sql = " Campo Data do processamento nao Informado.";
         $this->erro_campo = "k85_dtproc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k85_dtproc_dia"])){ 
         $sql  .= $virgula." k85_dtproc = null ";
         $virgula = ",";
         if(trim($this->k85_dtproc) == null ){ 
           $this->erro_sql = " Campo Data do processamento nao Informado.";
           $this->erro_campo = "k85_dtproc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k85_dtarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k85_dtarq_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k85_dtarq_dia"] !="") ){ 
       $sql  .= $virgula." k85_dtarq = '$this->k85_dtarq' ";
       $virgula = ",";
       if(trim($this->k85_dtarq) == null ){ 
         $this->erro_sql = " Campo Data do arquivo nao Informado.";
         $this->erro_campo = "k85_dtarq_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k85_dtarq_dia"])){ 
         $sql  .= $virgula." k85_dtarq = null ";
         $virgula = ",";
         if(trim($this->k85_dtarq) == null ){ 
           $this->erro_sql = " Campo Data do arquivo nao Informado.";
           $this->erro_campo = "k85_dtarq_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k85_convenio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k85_convenio"])){ 
       $sql  .= $virgula." k85_convenio = '$this->k85_convenio' ";
       $virgula = ",";
       if(trim($this->k85_convenio) == null ){ 
         $this->erro_sql = " Campo Convenio nao Informado.";
         $this->erro_campo = "k85_convenio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k85_seqarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k85_seqarq"])){ 
       $sql  .= $virgula." k85_seqarq = $this->k85_seqarq ";
       $virgula = ",";
       if(trim($this->k85_seqarq) == null ){ 
         $this->erro_sql = " Campo Sequencial do arquivo nao Informado.";
         $this->erro_campo = "k85_seqarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k85_nomearq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k85_nomearq"])){ 
       $sql  .= $virgula." k85_nomearq = '$this->k85_nomearq' ";
       $virgula = ",";
       if(trim($this->k85_nomearq) == null ){ 
         $this->erro_sql = " Campo Nome do arquivo nao Informado.";
         $this->erro_campo = "k85_nomearq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k85_tipoinclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k85_tipoinclusao"])){ 
       $sql  .= $virgula." k85_tipoinclusao = $this->k85_tipoinclusao ";
       $virgula = ",";
       if(trim($this->k85_tipoinclusao) == null ){ 
         $this->erro_sql = " Campo Tipo de inclusão nao Informado.";
         $this->erro_campo = "k85_tipoinclusao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k85_conteudo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k85_conteudo"])){ 
       $sql  .= $virgula." k85_conteudo = '$this->k85_conteudo' ";
       $virgula = ",";
       if(trim($this->k85_conteudo) == null ){ 
         $this->erro_sql = " Campo Conteudo do arquivo nao Informado.";
         $this->erro_campo = "k85_conteudo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k85_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k85_cnpj"])){ 
       $sql  .= $virgula." k85_cnpj = '$this->k85_cnpj' ";
       $virgula = ",";
       if(trim($this->k85_cnpj) == null ){ 
         $this->erro_sql = " Campo CNPJ nao Informado.";
         $this->erro_campo = "k85_cnpj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k85_sequencial!=null){
       $sql .= " k85_sequencial = $this->k85_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k85_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10051,'$this->k85_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k85_sequencial"]) || $this->k85_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,1728,10051,'".AddSlashes(pg_result($resaco,$conresaco,'k85_sequencial'))."','$this->k85_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k85_codbco"]) || $this->k85_codbco != "")
           $resac = db_query("insert into db_acount values($acount,1728,10052,'".AddSlashes(pg_result($resaco,$conresaco,'k85_codbco'))."','$this->k85_codbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k85_dtproc"]) || $this->k85_dtproc != "")
           $resac = db_query("insert into db_acount values($acount,1728,10053,'".AddSlashes(pg_result($resaco,$conresaco,'k85_dtproc'))."','$this->k85_dtproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k85_dtarq"]) || $this->k85_dtarq != "")
           $resac = db_query("insert into db_acount values($acount,1728,10054,'".AddSlashes(pg_result($resaco,$conresaco,'k85_dtarq'))."','$this->k85_dtarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k85_convenio"]) || $this->k85_convenio != "")
           $resac = db_query("insert into db_acount values($acount,1728,10055,'".AddSlashes(pg_result($resaco,$conresaco,'k85_convenio'))."','$this->k85_convenio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k85_seqarq"]) || $this->k85_seqarq != "")
           $resac = db_query("insert into db_acount values($acount,1728,10056,'".AddSlashes(pg_result($resaco,$conresaco,'k85_seqarq'))."','$this->k85_seqarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k85_nomearq"]) || $this->k85_nomearq != "")
           $resac = db_query("insert into db_acount values($acount,1728,10057,'".AddSlashes(pg_result($resaco,$conresaco,'k85_nomearq'))."','$this->k85_nomearq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k85_tipoinclusao"]) || $this->k85_tipoinclusao != "")
           $resac = db_query("insert into db_acount values($acount,1728,10140,'".AddSlashes(pg_result($resaco,$conresaco,'k85_tipoinclusao'))."','$this->k85_tipoinclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k85_conteudo"]) || $this->k85_conteudo != "")
           $resac = db_query("insert into db_acount values($acount,1728,10058,'".AddSlashes(pg_result($resaco,$conresaco,'k85_conteudo'))."','$this->k85_conteudo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k85_cnpj"]) || $this->k85_cnpj != "")
           $resac = db_query("insert into db_acount values($acount,1728,17939,'".AddSlashes(pg_result($resaco,$conresaco,'k85_cnpj'))."','$this->k85_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Extrato bancario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k85_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Extrato bancario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k85_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k85_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10051,'$k85_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1728,10051,'','".AddSlashes(pg_result($resaco,$iresaco,'k85_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1728,10052,'','".AddSlashes(pg_result($resaco,$iresaco,'k85_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1728,10053,'','".AddSlashes(pg_result($resaco,$iresaco,'k85_dtproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1728,10054,'','".AddSlashes(pg_result($resaco,$iresaco,'k85_dtarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1728,10055,'','".AddSlashes(pg_result($resaco,$iresaco,'k85_convenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1728,10056,'','".AddSlashes(pg_result($resaco,$iresaco,'k85_seqarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1728,10057,'','".AddSlashes(pg_result($resaco,$iresaco,'k85_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1728,10140,'','".AddSlashes(pg_result($resaco,$iresaco,'k85_tipoinclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1728,10058,'','".AddSlashes(pg_result($resaco,$iresaco,'k85_conteudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1728,17939,'','".AddSlashes(pg_result($resaco,$iresaco,'k85_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from extrato
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k85_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k85_sequencial = $k85_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Extrato bancario nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k85_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Extrato bancario nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k85_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:extrato";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k85_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from extrato ";
     $sql .= "      inner join bancos  on  bancos.codbco = extrato.k85_codbco";
     $sql2 = "";
     if($dbwhere==""){
       if($k85_sequencial!=null ){
         $sql2 .= " where extrato.k85_sequencial = $k85_sequencial "; 
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
   function sql_query_file ( $k85_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from extrato ";
     $sql2 = "";
     if($dbwhere==""){
       if($k85_sequencial!=null ){
         $sql2 .= " where extrato.k85_sequencial = $k85_sequencial "; 
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