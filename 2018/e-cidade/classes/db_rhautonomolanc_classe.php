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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhautonomolanc
class cl_rhautonomolanc { 
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
   var $rh89_sequencial = 0; 
   var $rh89_numcgm = 0; 
   var $rh89_codord = 0; 
   var $rh89_anousu = 0; 
   var $rh89_mesusu = 0; 
   var $rh89_dataliq_dia = null; 
   var $rh89_dataliq_mes = null; 
   var $rh89_dataliq_ano = null; 
   var $rh89_dataliq = null; 
   var $rh89_valorserv = 0; 
   var $rh89_valorretirrf = 0; 
   var $rh89_valorretinss = 0; 
   var $rh89_instit = 0; 
   var $rh89_processado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh89_sequencial = int4 = Sequencial 
                 rh89_numcgm = int4 = Cgm 
                 rh89_codord = int4 = Ordem de Pagamento 
                 rh89_anousu = int4 = Exercício 
                 rh89_mesusu = int4 = Mês 
                 rh89_dataliq = date = Data Liquidação 
                 rh89_valorserv = float4 = Valor Serviço 
                 rh89_valorretirrf = float4 = Valor Retenção IRRF 
                 rh89_valorretinss = float4 = Valor Retenção INSS 
                 rh89_instit = int4 = Instituição 
                 rh89_processado = bool = Processado 
                 ";
   //funcao construtor da classe 
   function cl_rhautonomolanc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhautonomolanc"); 
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
       $this->rh89_sequencial = ($this->rh89_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh89_sequencial"]:$this->rh89_sequencial);
       $this->rh89_numcgm = ($this->rh89_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["rh89_numcgm"]:$this->rh89_numcgm);
       $this->rh89_codord = ($this->rh89_codord == ""?@$GLOBALS["HTTP_POST_VARS"]["rh89_codord"]:$this->rh89_codord);
       $this->rh89_anousu = ($this->rh89_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh89_anousu"]:$this->rh89_anousu);
       $this->rh89_mesusu = ($this->rh89_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh89_mesusu"]:$this->rh89_mesusu);
       if($this->rh89_dataliq == ""){
         $this->rh89_dataliq_dia = ($this->rh89_dataliq_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh89_dataliq_dia"]:$this->rh89_dataliq_dia);
         $this->rh89_dataliq_mes = ($this->rh89_dataliq_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh89_dataliq_mes"]:$this->rh89_dataliq_mes);
         $this->rh89_dataliq_ano = ($this->rh89_dataliq_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh89_dataliq_ano"]:$this->rh89_dataliq_ano);
         if($this->rh89_dataliq_dia != ""){
            $this->rh89_dataliq = $this->rh89_dataliq_ano."-".$this->rh89_dataliq_mes."-".$this->rh89_dataliq_dia;
         }
       }
       $this->rh89_valorserv = ($this->rh89_valorserv == ""?@$GLOBALS["HTTP_POST_VARS"]["rh89_valorserv"]:$this->rh89_valorserv);
       $this->rh89_valorretirrf = ($this->rh89_valorretirrf == ""?@$GLOBALS["HTTP_POST_VARS"]["rh89_valorretirrf"]:$this->rh89_valorretirrf);
       $this->rh89_valorretinss = ($this->rh89_valorretinss == ""?@$GLOBALS["HTTP_POST_VARS"]["rh89_valorretinss"]:$this->rh89_valorretinss);
       $this->rh89_instit = ($this->rh89_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh89_instit"]:$this->rh89_instit);
       $this->rh89_processado = ($this->rh89_processado == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh89_processado"]:$this->rh89_processado);
     }else{
       $this->rh89_sequencial = ($this->rh89_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh89_sequencial"]:$this->rh89_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh89_sequencial){ 
      $this->atualizacampos();
     if($this->rh89_numcgm == null ){ 
       $this->erro_sql = " Campo Cgm nao Informado.";
       $this->erro_campo = "rh89_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh89_codord == null ){ 
       $this->erro_sql = " Campo Ordem de Pagamento nao Informado.";
       $this->erro_campo = "rh89_codord";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh89_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "rh89_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh89_mesusu == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "rh89_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh89_dataliq == null ){ 
       $this->erro_sql = " Campo Data Liquidação nao Informado.";
       $this->erro_campo = "rh89_dataliq_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh89_valorserv == null ){ 
       $this->erro_sql = " Campo Valor Serviço nao Informado.";
       $this->erro_campo = "rh89_valorserv";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh89_valorretirrf == null ){ 
       $this->erro_sql = " Campo Valor Retenção IRRF nao Informado.";
       $this->erro_campo = "rh89_valorretirrf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh89_valorretinss == null ){ 
       $this->erro_sql = " Campo Valor Retenção INSS nao Informado.";
       $this->erro_campo = "rh89_valorretinss";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh89_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "rh89_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh89_processado == null ){ 
       $this->erro_sql = " Campo Processado nao Informado.";
       $this->erro_campo = "rh89_processado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh89_sequencial == "" || $rh89_sequencial == null ){
       $result = db_query("select nextval('rhautonomolanc_rh89_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhautonomolanc_rh89_sequencial_seq do campo: rh89_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh89_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhautonomolanc_rh89_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh89_sequencial)){
         $this->erro_sql = " Campo rh89_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh89_sequencial = $rh89_sequencial; 
       }
     }
     if(($this->rh89_sequencial == null) || ($this->rh89_sequencial == "") ){ 
       $this->erro_sql = " Campo rh89_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhautonomolanc(
                                       rh89_sequencial 
                                      ,rh89_numcgm 
                                      ,rh89_codord 
                                      ,rh89_anousu 
                                      ,rh89_mesusu 
                                      ,rh89_dataliq 
                                      ,rh89_valorserv 
                                      ,rh89_valorretirrf 
                                      ,rh89_valorretinss 
                                      ,rh89_instit 
                                      ,rh89_processado 
                       )
                values (
                                $this->rh89_sequencial 
                               ,$this->rh89_numcgm 
                               ,$this->rh89_codord 
                               ,$this->rh89_anousu 
                               ,$this->rh89_mesusu 
                               ,".($this->rh89_dataliq == "null" || $this->rh89_dataliq == ""?"null":"'".$this->rh89_dataliq."'")." 
                               ,$this->rh89_valorserv 
                               ,$this->rh89_valorretirrf 
                               ,$this->rh89_valorretinss 
                               ,$this->rh89_instit 
                               ,'$this->rh89_processado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lançamentos de Autonomos ($this->rh89_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lançamentos de Autonomos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lançamentos de Autonomos ($this->rh89_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh89_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh89_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17522,'$this->rh89_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3096,17522,'','".AddSlashes(pg_result($resaco,0,'rh89_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3096,17523,'','".AddSlashes(pg_result($resaco,0,'rh89_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3096,17524,'','".AddSlashes(pg_result($resaco,0,'rh89_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3096,17525,'','".AddSlashes(pg_result($resaco,0,'rh89_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3096,17526,'','".AddSlashes(pg_result($resaco,0,'rh89_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3096,17527,'','".AddSlashes(pg_result($resaco,0,'rh89_dataliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3096,17528,'','".AddSlashes(pg_result($resaco,0,'rh89_valorserv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3096,17529,'','".AddSlashes(pg_result($resaco,0,'rh89_valorretirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3096,17530,'','".AddSlashes(pg_result($resaco,0,'rh89_valorretinss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3096,17531,'','".AddSlashes(pg_result($resaco,0,'rh89_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3096,17532,'','".AddSlashes(pg_result($resaco,0,'rh89_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh89_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhautonomolanc set ";
     $virgula = "";
     if(trim($this->rh89_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh89_sequencial"])){ 
       $sql  .= $virgula." rh89_sequencial = $this->rh89_sequencial ";
       $virgula = ",";
       if(trim($this->rh89_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh89_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh89_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh89_numcgm"])){ 
       $sql  .= $virgula." rh89_numcgm = $this->rh89_numcgm ";
       $virgula = ",";
       if(trim($this->rh89_numcgm) == null ){ 
         $this->erro_sql = " Campo Cgm nao Informado.";
         $this->erro_campo = "rh89_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh89_codord)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh89_codord"])){ 
       $sql  .= $virgula." rh89_codord = $this->rh89_codord ";
       $virgula = ",";
       if(trim($this->rh89_codord) == null ){ 
         $this->erro_sql = " Campo Ordem de Pagamento nao Informado.";
         $this->erro_campo = "rh89_codord";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh89_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh89_anousu"])){ 
       $sql  .= $virgula." rh89_anousu = $this->rh89_anousu ";
       $virgula = ",";
       if(trim($this->rh89_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "rh89_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh89_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh89_mesusu"])){ 
       $sql  .= $virgula." rh89_mesusu = $this->rh89_mesusu ";
       $virgula = ",";
       if(trim($this->rh89_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "rh89_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh89_dataliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh89_dataliq_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh89_dataliq_dia"] !="") ){ 
       $sql  .= $virgula." rh89_dataliq = '$this->rh89_dataliq' ";
       $virgula = ",";
       if(trim($this->rh89_dataliq) == null ){ 
         $this->erro_sql = " Campo Data Liquidação nao Informado.";
         $this->erro_campo = "rh89_dataliq_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh89_dataliq_dia"])){ 
         $sql  .= $virgula." rh89_dataliq = null ";
         $virgula = ",";
         if(trim($this->rh89_dataliq) == null ){ 
           $this->erro_sql = " Campo Data Liquidação nao Informado.";
           $this->erro_campo = "rh89_dataliq_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh89_valorserv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh89_valorserv"])){ 
       $sql  .= $virgula." rh89_valorserv = $this->rh89_valorserv ";
       $virgula = ",";
       if(trim($this->rh89_valorserv) == null ){ 
         $this->erro_sql = " Campo Valor Serviço nao Informado.";
         $this->erro_campo = "rh89_valorserv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh89_valorretirrf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh89_valorretirrf"])){ 
       $sql  .= $virgula." rh89_valorretirrf = $this->rh89_valorretirrf ";
       $virgula = ",";
       if(trim($this->rh89_valorretirrf) == null ){ 
         $this->erro_sql = " Campo Valor Retenção IRRF nao Informado.";
         $this->erro_campo = "rh89_valorretirrf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh89_valorretinss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh89_valorretinss"])){ 
       $sql  .= $virgula." rh89_valorretinss = $this->rh89_valorretinss ";
       $virgula = ",";
       if(trim($this->rh89_valorretinss) == null ){ 
         $this->erro_sql = " Campo Valor Retenção INSS nao Informado.";
         $this->erro_campo = "rh89_valorretinss";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh89_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh89_instit"])){ 
       $sql  .= $virgula." rh89_instit = $this->rh89_instit ";
       $virgula = ",";
       if(trim($this->rh89_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "rh89_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh89_processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh89_processado"])){ 
       $sql  .= $virgula." rh89_processado = '$this->rh89_processado' ";
       $virgula = ",";
       if(trim($this->rh89_processado) == null ){ 
         $this->erro_sql = " Campo Processado nao Informado.";
         $this->erro_campo = "rh89_processado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh89_sequencial!=null){
       $sql .= " rh89_sequencial = $this->rh89_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh89_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17522,'$this->rh89_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh89_sequencial"]) || $this->rh89_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3096,17522,'".AddSlashes(pg_result($resaco,$conresaco,'rh89_sequencial'))."','$this->rh89_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh89_numcgm"]) || $this->rh89_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,3096,17523,'".AddSlashes(pg_result($resaco,$conresaco,'rh89_numcgm'))."','$this->rh89_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh89_codord"]) || $this->rh89_codord != "")
           $resac = db_query("insert into db_acount values($acount,3096,17524,'".AddSlashes(pg_result($resaco,$conresaco,'rh89_codord'))."','$this->rh89_codord',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh89_anousu"]) || $this->rh89_anousu != "")
           $resac = db_query("insert into db_acount values($acount,3096,17525,'".AddSlashes(pg_result($resaco,$conresaco,'rh89_anousu'))."','$this->rh89_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh89_mesusu"]) || $this->rh89_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,3096,17526,'".AddSlashes(pg_result($resaco,$conresaco,'rh89_mesusu'))."','$this->rh89_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh89_dataliq"]) || $this->rh89_dataliq != "")
           $resac = db_query("insert into db_acount values($acount,3096,17527,'".AddSlashes(pg_result($resaco,$conresaco,'rh89_dataliq'))."','$this->rh89_dataliq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh89_valorserv"]) || $this->rh89_valorserv != "")
           $resac = db_query("insert into db_acount values($acount,3096,17528,'".AddSlashes(pg_result($resaco,$conresaco,'rh89_valorserv'))."','$this->rh89_valorserv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh89_valorretirrf"]) || $this->rh89_valorretirrf != "")
           $resac = db_query("insert into db_acount values($acount,3096,17529,'".AddSlashes(pg_result($resaco,$conresaco,'rh89_valorretirrf'))."','$this->rh89_valorretirrf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh89_valorretinss"]) || $this->rh89_valorretinss != "")
           $resac = db_query("insert into db_acount values($acount,3096,17530,'".AddSlashes(pg_result($resaco,$conresaco,'rh89_valorretinss'))."','$this->rh89_valorretinss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh89_instit"]) || $this->rh89_instit != "")
           $resac = db_query("insert into db_acount values($acount,3096,17531,'".AddSlashes(pg_result($resaco,$conresaco,'rh89_instit'))."','$this->rh89_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh89_processado"]) || $this->rh89_processado != "")
           $resac = db_query("insert into db_acount values($acount,3096,17532,'".AddSlashes(pg_result($resaco,$conresaco,'rh89_processado'))."','$this->rh89_processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamentos de Autonomos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh89_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamentos de Autonomos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh89_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh89_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh89_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh89_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17522,'$rh89_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3096,17522,'','".AddSlashes(pg_result($resaco,$iresaco,'rh89_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3096,17523,'','".AddSlashes(pg_result($resaco,$iresaco,'rh89_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3096,17524,'','".AddSlashes(pg_result($resaco,$iresaco,'rh89_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3096,17525,'','".AddSlashes(pg_result($resaco,$iresaco,'rh89_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3096,17526,'','".AddSlashes(pg_result($resaco,$iresaco,'rh89_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3096,17527,'','".AddSlashes(pg_result($resaco,$iresaco,'rh89_dataliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3096,17528,'','".AddSlashes(pg_result($resaco,$iresaco,'rh89_valorserv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3096,17529,'','".AddSlashes(pg_result($resaco,$iresaco,'rh89_valorretirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3096,17530,'','".AddSlashes(pg_result($resaco,$iresaco,'rh89_valorretinss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3096,17531,'','".AddSlashes(pg_result($resaco,$iresaco,'rh89_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3096,17532,'','".AddSlashes(pg_result($resaco,$iresaco,'rh89_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhautonomolanc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh89_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh89_sequencial = $rh89_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamentos de Autonomos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh89_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamentos de Autonomos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh89_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh89_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhautonomolanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh89_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhautonomolanc ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhautonomolanc.rh89_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = rhautonomolanc.rh89_instit";
     $sql .= "      inner join pagordem  on  pagordem.e50_codord = rhautonomolanc.rh89_codord";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pagordem.e50_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
     $sql2 = "";
     if($dbwhere==""){
       if($rh89_sequencial!=null ){
         $sql2 .= " where rhautonomolanc.rh89_sequencial = $rh89_sequencial "; 
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
   function sql_query_file ( $rh89_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhautonomolanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh89_sequencial!=null ){
         $sql2 .= " where rhautonomolanc.rh89_sequencial = $rh89_sequencial "; 
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
  
   function sql_query_sefip( $rh89_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhautonomolanc ";
     $sql .= "      inner join cgm                   on cgm.z01_numcgm                            = rhautonomolanc.rh89_numcgm";
     $sql .= "      inner join rhsefiprhautonomolanc on rhsefiprhautonomolanc.rh92_rhautonomolanc = rhautonomolanc.rh89_sequencial";
     $sql .= "      inner join rhsefip               on rhsefip.rh90_sequencial                   = rhsefiprhautonomolanc.rh92_rhsefip ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($rh89_sequencial!=null ){
         $sql2 .= " where rhautonomolanc.rh89_sequencial = $rh89_sequencial "; 
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