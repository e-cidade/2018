<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: Caixa
//CLASSE DA ENTIDADE saltes
class cl_saltes { 
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
   var $k13_conta = 0; 
   var $k13_reduz = 0; 
   var $k13_descr = null; 
   var $k13_saldo = 0; 
   var $k13_ident = null; 
   var $k13_vlratu = 0; 
   var $k13_datvlr_dia = null; 
   var $k13_datvlr_mes = null; 
   var $k13_datvlr_ano = null; 
   var $k13_datvlr = null; 
   var $k13_limite_dia = null; 
   var $k13_limite_mes = null; 
   var $k13_limite_ano = null; 
   var $k13_limite = null; 
   var $k13_dtimplantacao_dia = null; 
   var $k13_dtimplantacao_mes = null; 
   var $k13_dtimplantacao_ano = null; 
   var $k13_dtimplantacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k13_conta = int4 = Código Conta 
                 k13_reduz = int4 = Reduzido 
                 k13_descr = varchar(40) = Descrição  Conta 
                 k13_saldo = float8 = Saldo da Conta 
                 k13_ident = char(15) = Identificacao da conta 
                 k13_vlratu = float8 = Valor Atualizado 
                 k13_datvlr = date = Data Atualização 
                 k13_limite = date = Data Limite 
                 k13_dtimplantacao = date = Data da Implantação da Conta 
                 ";
   //funcao construtor da classe 
   function cl_saltes() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("saltes"); 
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
       $this->k13_conta = ($this->k13_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_conta"]:$this->k13_conta);
       $this->k13_reduz = ($this->k13_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_reduz"]:$this->k13_reduz);
       $this->k13_descr = ($this->k13_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_descr"]:$this->k13_descr);
       $this->k13_saldo = ($this->k13_saldo == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_saldo"]:$this->k13_saldo);
       $this->k13_ident = ($this->k13_ident == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_ident"]:$this->k13_ident);
       $this->k13_vlratu = ($this->k13_vlratu == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_vlratu"]:$this->k13_vlratu);
       if($this->k13_datvlr == ""){
         $this->k13_datvlr_dia = ($this->k13_datvlr_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_datvlr_dia"]:$this->k13_datvlr_dia);
         $this->k13_datvlr_mes = ($this->k13_datvlr_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_datvlr_mes"]:$this->k13_datvlr_mes);
         $this->k13_datvlr_ano = ($this->k13_datvlr_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_datvlr_ano"]:$this->k13_datvlr_ano);
         if($this->k13_datvlr_dia != ""){
            $this->k13_datvlr = $this->k13_datvlr_ano."-".$this->k13_datvlr_mes."-".$this->k13_datvlr_dia;
         }
       }
       if($this->k13_limite == ""){
         $this->k13_limite_dia = ($this->k13_limite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_limite_dia"]:$this->k13_limite_dia);
         $this->k13_limite_mes = ($this->k13_limite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_limite_mes"]:$this->k13_limite_mes);
         $this->k13_limite_ano = ($this->k13_limite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_limite_ano"]:$this->k13_limite_ano);
         if($this->k13_limite_dia != ""){
            $this->k13_limite = $this->k13_limite_ano."-".$this->k13_limite_mes."-".$this->k13_limite_dia;
         }
       }
       if($this->k13_dtimplantacao == ""){
         $this->k13_dtimplantacao_dia = ($this->k13_dtimplantacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_dtimplantacao_dia"]:$this->k13_dtimplantacao_dia);
         $this->k13_dtimplantacao_mes = ($this->k13_dtimplantacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_dtimplantacao_mes"]:$this->k13_dtimplantacao_mes);
         $this->k13_dtimplantacao_ano = ($this->k13_dtimplantacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_dtimplantacao_ano"]:$this->k13_dtimplantacao_ano);
         if($this->k13_dtimplantacao_dia != ""){
            $this->k13_dtimplantacao = $this->k13_dtimplantacao_ano."-".$this->k13_dtimplantacao_mes."-".$this->k13_dtimplantacao_dia;
         }
       }
     }else{
       $this->k13_conta = ($this->k13_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["k13_conta"]:$this->k13_conta);
     }
   }
   // funcao para inclusao
   function incluir ($k13_conta){ 
      $this->atualizacampos();
     if($this->k13_reduz == null ){ 
       $this->erro_sql = " Campo Reduzido nao Informado.";
       $this->erro_campo = "k13_reduz";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k13_descr == null ){ 
       $this->erro_sql = " Campo Descrição  Conta nao Informado.";
       $this->erro_campo = "k13_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k13_saldo == null ){ 
       $this->erro_sql = " Campo Saldo da Conta nao Informado.";
       $this->erro_campo = "k13_saldo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k13_vlratu == null ){ 
       $this->k13_vlratu = "0";
     }
     if($this->k13_limite == null ){ 
       $this->k13_limite = "null";
     }
     if($this->k13_dtimplantacao == null ){ 
       $this->erro_sql = " Campo Data da Implantação da Conta nao Informado.";
       $this->erro_campo = "k13_dtimplantacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->k13_conta = $k13_conta; 
     if(($this->k13_conta == null) || ($this->k13_conta == "") ){ 
       $this->erro_sql = " Campo k13_conta nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into saltes(
                                       k13_conta 
                                      ,k13_reduz 
                                      ,k13_descr 
                                      ,k13_saldo 
                                      ,k13_ident 
                                      ,k13_vlratu 
                                      ,k13_datvlr 
                                      ,k13_limite 
                                      ,k13_dtimplantacao 
                       )
                values (
                                $this->k13_conta 
                               ,$this->k13_reduz 
                               ,'$this->k13_descr' 
                               ,$this->k13_saldo 
                               ,'$this->k13_ident' 
                               ,$this->k13_vlratu 
                               ,".($this->k13_datvlr == "null" || $this->k13_datvlr == ""?"null":"'".$this->k13_datvlr."'")." 
                               ,".($this->k13_limite == "null" || $this->k13_limite == ""?"null":"'".$this->k13_limite."'")." 
                               ,".($this->k13_dtimplantacao == "null" || $this->k13_dtimplantacao == ""?"null":"'".$this->k13_dtimplantacao."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Saldo Tesuoraria ($this->k13_conta) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Saldo Tesuoraria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Saldo Tesuoraria ($this->k13_conta) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k13_conta;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k13_conta));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1173,'$this->k13_conta','I')");
       $resac = db_query("insert into db_acount values($acount,212,1173,'','".AddSlashes(pg_result($resaco,0,'k13_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,212,6012,'','".AddSlashes(pg_result($resaco,0,'k13_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,212,1916,'','".AddSlashes(pg_result($resaco,0,'k13_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,212,1174,'','".AddSlashes(pg_result($resaco,0,'k13_saldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,212,1175,'','".AddSlashes(pg_result($resaco,0,'k13_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,212,1176,'','".AddSlashes(pg_result($resaco,0,'k13_vlratu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,212,1177,'','".AddSlashes(pg_result($resaco,0,'k13_datvlr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,212,6896,'','".AddSlashes(pg_result($resaco,0,'k13_limite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,212,14209,'','".AddSlashes(pg_result($resaco,0,'k13_dtimplantacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k13_conta=null) { 
      $this->atualizacampos();
     $sql = " update saltes set ";
     $virgula = "";
     if(trim($this->k13_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k13_conta"])){ 
       $sql  .= $virgula." k13_conta = $this->k13_conta ";
       $virgula = ",";
       if(trim($this->k13_conta) == null ){ 
         $this->erro_sql = " Campo Código Conta nao Informado.";
         $this->erro_campo = "k13_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k13_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k13_reduz"])){ 
       $sql  .= $virgula." k13_reduz = $this->k13_reduz ";
       $virgula = ",";
       if(trim($this->k13_reduz) == null ){ 
         $this->erro_sql = " Campo Reduzido nao Informado.";
         $this->erro_campo = "k13_reduz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k13_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k13_descr"])){ 
       $sql  .= $virgula." k13_descr = '$this->k13_descr' ";
       $virgula = ",";
       if(trim($this->k13_descr) == null ){ 
         $this->erro_sql = " Campo Descrição  Conta nao Informado.";
         $this->erro_campo = "k13_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k13_saldo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k13_saldo"])){ 
       $sql  .= $virgula." k13_saldo = $this->k13_saldo ";
       $virgula = ",";
       if(trim($this->k13_saldo) == null ){ 
         $this->erro_sql = " Campo Saldo da Conta nao Informado.";
         $this->erro_campo = "k13_saldo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k13_ident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k13_ident"])){ 
       $sql  .= $virgula." k13_ident = '$this->k13_ident' ";
       $virgula = ",";
     }
     if(trim($this->k13_vlratu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k13_vlratu"])){ 
        if(trim($this->k13_vlratu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k13_vlratu"])){ 
           $this->k13_vlratu = "0" ; 
        } 
       $sql  .= $virgula." k13_vlratu = $this->k13_vlratu ";
       $virgula = ",";
     }
     if(trim($this->k13_datvlr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k13_datvlr_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k13_datvlr_dia"] !="") ){ 
       $sql  .= $virgula." k13_datvlr = '$this->k13_datvlr' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k13_datvlr_dia"])){ 
         $sql  .= $virgula." k13_datvlr = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k13_limite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k13_limite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k13_limite_dia"] !="") ){ 
       $sql  .= $virgula." k13_limite = '$this->k13_limite' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k13_limite_dia"])){ 
         $sql  .= $virgula." k13_limite = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k13_dtimplantacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k13_dtimplantacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k13_dtimplantacao_dia"] !="") ){ 
       $sql  .= $virgula." k13_dtimplantacao = '$this->k13_dtimplantacao' ";
       $virgula = ",";
       if(trim($this->k13_dtimplantacao) == null ){ 
         $this->erro_sql = " Campo Data da Implantação da Conta nao Informado.";
         $this->erro_campo = "k13_dtimplantacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k13_dtimplantacao_dia"])){ 
         $sql  .= $virgula." k13_dtimplantacao = null ";
         $virgula = ",";
         if(trim($this->k13_dtimplantacao) == null ){ 
           $this->erro_sql = " Campo Data da Implantação da Conta nao Informado.";
           $this->erro_campo = "k13_dtimplantacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($k13_conta!=null){
       $sql .= " k13_conta = $this->k13_conta";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k13_conta));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1173,'$this->k13_conta','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k13_conta"]) || $this->k13_conta != "")
           $resac = db_query("insert into db_acount values($acount,212,1173,'".AddSlashes(pg_result($resaco,$conresaco,'k13_conta'))."','$this->k13_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k13_reduz"]) || $this->k13_reduz != "")
           $resac = db_query("insert into db_acount values($acount,212,6012,'".AddSlashes(pg_result($resaco,$conresaco,'k13_reduz'))."','$this->k13_reduz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k13_descr"]) || $this->k13_descr != "")
           $resac = db_query("insert into db_acount values($acount,212,1916,'".AddSlashes(pg_result($resaco,$conresaco,'k13_descr'))."','$this->k13_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k13_saldo"]) || $this->k13_saldo != "")
           $resac = db_query("insert into db_acount values($acount,212,1174,'".AddSlashes(pg_result($resaco,$conresaco,'k13_saldo'))."','$this->k13_saldo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k13_ident"]) || $this->k13_ident != "")
           $resac = db_query("insert into db_acount values($acount,212,1175,'".AddSlashes(pg_result($resaco,$conresaco,'k13_ident'))."','$this->k13_ident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k13_vlratu"]) || $this->k13_vlratu != "")
           $resac = db_query("insert into db_acount values($acount,212,1176,'".AddSlashes(pg_result($resaco,$conresaco,'k13_vlratu'))."','$this->k13_vlratu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k13_datvlr"]) || $this->k13_datvlr != "")
           $resac = db_query("insert into db_acount values($acount,212,1177,'".AddSlashes(pg_result($resaco,$conresaco,'k13_datvlr'))."','$this->k13_datvlr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k13_limite"]) || $this->k13_limite != "")
           $resac = db_query("insert into db_acount values($acount,212,6896,'".AddSlashes(pg_result($resaco,$conresaco,'k13_limite'))."','$this->k13_limite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k13_dtimplantacao"]) || $this->k13_dtimplantacao != "")
           $resac = db_query("insert into db_acount values($acount,212,14209,'".AddSlashes(pg_result($resaco,$conresaco,'k13_dtimplantacao'))."','$this->k13_dtimplantacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saldo Tesuoraria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k13_conta;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saldo Tesuoraria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k13_conta;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k13_conta;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k13_conta=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k13_conta));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1173,'$k13_conta','E')");
         $resac = db_query("insert into db_acount values($acount,212,1173,'','".AddSlashes(pg_result($resaco,$iresaco,'k13_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,212,6012,'','".AddSlashes(pg_result($resaco,$iresaco,'k13_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,212,1916,'','".AddSlashes(pg_result($resaco,$iresaco,'k13_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,212,1174,'','".AddSlashes(pg_result($resaco,$iresaco,'k13_saldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,212,1175,'','".AddSlashes(pg_result($resaco,$iresaco,'k13_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,212,1176,'','".AddSlashes(pg_result($resaco,$iresaco,'k13_vlratu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,212,1177,'','".AddSlashes(pg_result($resaco,$iresaco,'k13_datvlr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,212,6896,'','".AddSlashes(pg_result($resaco,$iresaco,'k13_limite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,212,14209,'','".AddSlashes(pg_result($resaco,$iresaco,'k13_dtimplantacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from saltes
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k13_conta != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k13_conta = $k13_conta ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saldo Tesuoraria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k13_conta;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saldo Tesuoraria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k13_conta;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k13_conta;
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
        $this->erro_sql   = "Record Vazio na Tabela:saltes";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k13_conta=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from saltes ";
     $sql .= "      inner join conplanoreduz  on  conplanoreduz.c61_reduz = saltes.k13_reduz and c61_anousu=".db_getsession("DB_anousu");
     $sql .= "      inner join conplanoexe    on  conplanoexe.c62_reduz = conplanoreduz.c61_reduz and c62_anousu=c61_anousu";
     $sql .= "      left  join conplanoconta  on  conplanoconta.c63_codcon = conplanoreduz.c61_codcon  and conplanoconta.c63_anousu=conplanoreduz.c61_anousu";
     $sql2 = "";
     if($dbwhere==""){
       if($k13_conta!=null ){
         $sql2 .= " where saltes.k13_conta = $k13_conta "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2!=""?" and ":" where ") . " c61_instit = " . db_getsession("DB_instit");
     $sql2 .= ($sql2!=""?" and ":" where ") . " c62_anousu = " . db_getsession("DB_anousu");
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
   function sql_query_file ( $k13_conta=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from saltes ";
     $sql2 = "";
     if($dbwhere==""){
       if($k13_conta!=null ){
         $sql2 .= " where saltes.k13_conta = $k13_conta "; 
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
   function sql_query_anousu ( $k13_conta=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from saltes ";
     $sql .= "      inner join conplanoreduz  on  conplanoreduz.c61_reduz = saltes.k13_reduz and c61_anousu=".db_getsession("DB_anousu");
     $sql .= "      inner join conplanoexe  on  conplanoexe.c62_reduz = conplanoreduz.c61_reduz and c61_anousu=c62_anousu";
     $sql .= "      inner join conplano     on  conplanoreduz.c61_codcon = conplano.c60_codcon and c61_anousu=c60_anousu";
     $sql .= "      left  join conplanoconta  on  conplanoconta.c63_codcon = conplanoreduz.c61_codcon  and conplanoconta.c63_anousu=conplanoreduz.c61_anousu";
     $sql2 = "";
     if($dbwhere==""){
       if($k13_conta!=null ){
         $sql2 .= " where saltes.k13_conta = $k13_conta ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql2 .= ($sql2!=""?" and ":" where ") . " c61_instit = " . db_getsession("DB_instit");
     $sql2 .= ($sql2!=""?" and ":" where ") . " c62_anousu = " . db_getsession("DB_anousu");
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
  
  /**
   * 
   * Verifica se o reduzido possue conta na Tesouraria
   * @return string
   */
  function sql_query_movimentacao_tesouraria ($k13_conta=null,$campos="*",$ordem=null,$dbwhere="") {
    
    $sql  = "select ";
    if ($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i=0;$i<sizeof($campos_sql);$i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= "       from saltes                                                                  ";
    $sql .= "            left join corrente    on corrente.k12_conta    = saltes.k13_reduz       ";
    $sql .= "            left join placaixarec on placaixarec.k81_conta = saltes.k13_reduz       ";
    $sql .= "            left join slip        on slip.k17_debito       = saltes.k13_reduz or    ";
    $sql .= "                                      slip.k17_credito      = saltes.k13_reduz      ";
  	$sql2 = "";
  	
    if ($dbwhere == "") {
      
      if ($k13_conta!=null ) {
         $sql2 .= " where saltes.k13_conta = $k13_conta "; 
      } 
    } else if($dbwhere != "") {
       $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    
    if ($ordem != null ) {
      
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for ($i=0; $i<sizeof($campos_sql); $i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  
}
?>