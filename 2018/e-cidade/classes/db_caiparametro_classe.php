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

//MODULO: caixa
//CLASSE DA ENTIDADE caiparametro
class cl_caiparametro { 
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
   var $k29_instit = 0; 
   var $k29_boletimzerado = 'f'; 
   var $k29_modslipnormal = 0; 
   var $k29_modsliptransf = 0; 
   var $k29_chqduplicado = 'f'; 
   var $k29_chqemitidonaoautent_dia = null; 
   var $k29_chqemitidonaoautent_mes = null; 
   var $k29_chqemitidonaoautent_ano = null; 
   var $k29_chqemitidonaoautent = null; 
   var $k29_saldoemitechq = 0; 
   var $k29_datasaldocontasextra_dia = null; 
   var $k29_datasaldocontasextra_mes = null; 
   var $k29_datasaldocontasextra_ano = null; 
   var $k29_datasaldocontasextra = null; 
   var $k29_trazdatacheque = 'f'; 
   var $k29_contassemmovimento = 'f'; 
   var $k29_orctiporecfundeb = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k29_instit = int4 = Instituição 
                 k29_boletimzerado = bool = Emissão de Boletim de caixa zerado 
                 k29_modslipnormal = int4 = Modelo de impressao do slip 
                 k29_modsliptransf = int4 = Modelo de impressao do slip de transferencia 
                 k29_chqduplicado = bool = Agenda - Permitir cheques duplicados 
                 k29_chqemitidonaoautent = date = Cheques emitidos e nao autenticados a partir de 
                 k29_saldoemitechq = int4 = Controlar saldo da conta ao emitir cheque 
                 k29_datasaldocontasextra = date = Data Implantação Saldo Extra 
                 k29_trazdatacheque = bool = Trazer data cheques pagamentos agenda 
                 k29_contassemmovimento = bool = Trazer Contas sem Movimento 
                 k29_orctiporecfundeb = int4 = Recurso Fundeb 
                 ";
   //funcao construtor da classe 
   function cl_caiparametro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("caiparametro"); 
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
       $this->k29_instit = ($this->k29_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k29_instit"]:$this->k29_instit);
       $this->k29_boletimzerado = ($this->k29_boletimzerado == "f"?@$GLOBALS["HTTP_POST_VARS"]["k29_boletimzerado"]:$this->k29_boletimzerado);
       $this->k29_modslipnormal = ($this->k29_modslipnormal == ""?@$GLOBALS["HTTP_POST_VARS"]["k29_modslipnormal"]:$this->k29_modslipnormal);
       $this->k29_modsliptransf = ($this->k29_modsliptransf == ""?@$GLOBALS["HTTP_POST_VARS"]["k29_modsliptransf"]:$this->k29_modsliptransf);
       $this->k29_chqduplicado = ($this->k29_chqduplicado == "f"?@$GLOBALS["HTTP_POST_VARS"]["k29_chqduplicado"]:$this->k29_chqduplicado);
       if($this->k29_chqemitidonaoautent == ""){
         $this->k29_chqemitidonaoautent_dia = ($this->k29_chqemitidonaoautent_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k29_chqemitidonaoautent_dia"]:$this->k29_chqemitidonaoautent_dia);
         $this->k29_chqemitidonaoautent_mes = ($this->k29_chqemitidonaoautent_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k29_chqemitidonaoautent_mes"]:$this->k29_chqemitidonaoautent_mes);
         $this->k29_chqemitidonaoautent_ano = ($this->k29_chqemitidonaoautent_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k29_chqemitidonaoautent_ano"]:$this->k29_chqemitidonaoautent_ano);
         if($this->k29_chqemitidonaoautent_dia != ""){
            $this->k29_chqemitidonaoautent = $this->k29_chqemitidonaoautent_ano."-".$this->k29_chqemitidonaoautent_mes."-".$this->k29_chqemitidonaoautent_dia;
         }
       }
       $this->k29_saldoemitechq = ($this->k29_saldoemitechq == ""?@$GLOBALS["HTTP_POST_VARS"]["k29_saldoemitechq"]:$this->k29_saldoemitechq);
       if($this->k29_datasaldocontasextra == ""){
         $this->k29_datasaldocontasextra_dia = ($this->k29_datasaldocontasextra_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k29_datasaldocontasextra_dia"]:$this->k29_datasaldocontasextra_dia);
         $this->k29_datasaldocontasextra_mes = ($this->k29_datasaldocontasextra_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k29_datasaldocontasextra_mes"]:$this->k29_datasaldocontasextra_mes);
         $this->k29_datasaldocontasextra_ano = ($this->k29_datasaldocontasextra_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k29_datasaldocontasextra_ano"]:$this->k29_datasaldocontasextra_ano);
         if($this->k29_datasaldocontasextra_dia != ""){
            $this->k29_datasaldocontasextra = $this->k29_datasaldocontasextra_ano."-".$this->k29_datasaldocontasextra_mes."-".$this->k29_datasaldocontasextra_dia;
         }
       }
       $this->k29_trazdatacheque = ($this->k29_trazdatacheque == "f"?@$GLOBALS["HTTP_POST_VARS"]["k29_trazdatacheque"]:$this->k29_trazdatacheque);
       $this->k29_contassemmovimento = ($this->k29_contassemmovimento == "f"?@$GLOBALS["HTTP_POST_VARS"]["k29_contassemmovimento"]:$this->k29_contassemmovimento);
       $this->k29_orctiporecfundeb = ($this->k29_orctiporecfundeb == ""?@$GLOBALS["HTTP_POST_VARS"]["k29_orctiporecfundeb"]:$this->k29_orctiporecfundeb);
     }else{
       $this->k29_instit = ($this->k29_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k29_instit"]:$this->k29_instit);
     }
   }
   // funcao para inclusao
   function incluir ($k29_instit){ 
      $this->atualizacampos();
     if($this->k29_boletimzerado == null ){ 
       $this->erro_sql = " Campo Emissão de Boletim de caixa zerado nao Informado.";
       $this->erro_campo = "k29_boletimzerado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k29_modslipnormal == null ){ 
       $this->erro_sql = " Campo Modelo de impressao do slip nao Informado.";
       $this->erro_campo = "k29_modslipnormal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k29_modsliptransf == null ){ 
       $this->erro_sql = " Campo Modelo de impressao do slip de transferencia nao Informado.";
       $this->erro_campo = "k29_modsliptransf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k29_chqduplicado == null ){ 
       $this->erro_sql = " Campo Agenda - Permitir cheques duplicados nao Informado.";
       $this->erro_campo = "k29_chqduplicado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k29_chqemitidonaoautent == null ){ 
       $this->k29_chqemitidonaoautent = "null";
     }
     if($this->k29_saldoemitechq == null ){ 
       $this->erro_sql = " Campo Controlar saldo da conta ao emitir cheque nao Informado.";
       $this->erro_campo = "k29_saldoemitechq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k29_datasaldocontasextra == null ){ 
       $this->k29_datasaldocontasextra = "null";
     }
     if($this->k29_trazdatacheque == null ){ 
       $this->erro_sql = " Campo Trazer data cheques pagamentos agenda nao Informado.";
       $this->erro_campo = "k29_trazdatacheque";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k29_contassemmovimento == null ){ 
       $this->erro_sql = " Campo Trazer Contas sem Movimento nao Informado.";
       $this->erro_campo = "k29_contassemmovimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k29_orctiporecfundeb == null ){ 
       $this->erro_sql = " Campo Recurso Fundeb nao Informado.";
       $this->erro_campo = "k29_orctiporecfundeb";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->k29_instit = $k29_instit; 
     if(($this->k29_instit == null) || ($this->k29_instit == "") ){ 
       $this->erro_sql = " Campo k29_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into caiparametro(
                                       k29_instit 
                                      ,k29_boletimzerado 
                                      ,k29_modslipnormal 
                                      ,k29_modsliptransf 
                                      ,k29_chqduplicado 
                                      ,k29_chqemitidonaoautent 
                                      ,k29_saldoemitechq 
                                      ,k29_datasaldocontasextra 
                                      ,k29_trazdatacheque 
                                      ,k29_contassemmovimento 
                                      ,k29_orctiporecfundeb 
                       )
                values (
                                $this->k29_instit 
                               ,'$this->k29_boletimzerado' 
                               ,$this->k29_modslipnormal 
                               ,$this->k29_modsliptransf 
                               ,'$this->k29_chqduplicado' 
                               ,".($this->k29_chqemitidonaoautent == "null" || $this->k29_chqemitidonaoautent == ""?"null":"'".$this->k29_chqemitidonaoautent."'")." 
                               ,$this->k29_saldoemitechq 
                               ,".($this->k29_datasaldocontasextra == "null" || $this->k29_datasaldocontasextra == ""?"null":"'".$this->k29_datasaldocontasextra."'")." 
                               ,'$this->k29_trazdatacheque' 
                               ,'$this->k29_contassemmovimento' 
                               ,$this->k29_orctiporecfundeb 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "k29 ($this->k29_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "k29 já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "k29 ($this->k29_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k29_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k29_instit  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8803,'$this->k29_instit','I')");
         $resac = db_query("insert into db_acount values($acount,1503,8803,'','".AddSlashes(pg_result($resaco,0,'k29_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1503,8802,'','".AddSlashes(pg_result($resaco,0,'k29_boletimzerado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1503,9188,'','".AddSlashes(pg_result($resaco,0,'k29_modslipnormal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1503,9189,'','".AddSlashes(pg_result($resaco,0,'k29_modsliptransf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1503,9555,'','".AddSlashes(pg_result($resaco,0,'k29_chqduplicado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1503,10933,'','".AddSlashes(pg_result($resaco,0,'k29_chqemitidonaoautent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1503,10932,'','".AddSlashes(pg_result($resaco,0,'k29_saldoemitechq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1503,14540,'','".AddSlashes(pg_result($resaco,0,'k29_datasaldocontasextra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1503,14618,'','".AddSlashes(pg_result($resaco,0,'k29_trazdatacheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1503,15311,'','".AddSlashes(pg_result($resaco,0,'k29_contassemmovimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1503,20050,'','".AddSlashes(pg_result($resaco,0,'k29_orctiporecfundeb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k29_instit=null) { 
      $this->atualizacampos();
     $sql = " update caiparametro set ";
     $virgula = "";
     if(trim($this->k29_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k29_instit"])){ 
       $sql  .= $virgula." k29_instit = $this->k29_instit ";
       $virgula = ",";
       if(trim($this->k29_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "k29_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k29_boletimzerado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k29_boletimzerado"])){ 
       $sql  .= $virgula." k29_boletimzerado = '$this->k29_boletimzerado' ";
       $virgula = ",";
       if(trim($this->k29_boletimzerado) == null ){ 
         $this->erro_sql = " Campo Emissão de Boletim de caixa zerado nao Informado.";
         $this->erro_campo = "k29_boletimzerado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k29_modslipnormal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k29_modslipnormal"])){ 
       $sql  .= $virgula." k29_modslipnormal = $this->k29_modslipnormal ";
       $virgula = ",";
       if(trim($this->k29_modslipnormal) == null ){ 
         $this->erro_sql = " Campo Modelo de impressao do slip nao Informado.";
         $this->erro_campo = "k29_modslipnormal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k29_modsliptransf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k29_modsliptransf"])){ 
       $sql  .= $virgula." k29_modsliptransf = $this->k29_modsliptransf ";
       $virgula = ",";
       if(trim($this->k29_modsliptransf) == null ){ 
         $this->erro_sql = " Campo Modelo de impressao do slip de transferencia nao Informado.";
         $this->erro_campo = "k29_modsliptransf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k29_chqduplicado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k29_chqduplicado"])){ 
       $sql  .= $virgula." k29_chqduplicado = '$this->k29_chqduplicado' ";
       $virgula = ",";
       if(trim($this->k29_chqduplicado) == null ){ 
         $this->erro_sql = " Campo Agenda - Permitir cheques duplicados nao Informado.";
         $this->erro_campo = "k29_chqduplicado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k29_chqemitidonaoautent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k29_chqemitidonaoautent_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k29_chqemitidonaoautent_dia"] !="") ){ 
       $sql  .= $virgula." k29_chqemitidonaoautent = '$this->k29_chqemitidonaoautent' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k29_chqemitidonaoautent_dia"])){ 
         $sql  .= $virgula." k29_chqemitidonaoautent = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k29_saldoemitechq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k29_saldoemitechq"])){ 
       $sql  .= $virgula." k29_saldoemitechq = $this->k29_saldoemitechq ";
       $virgula = ",";
       if(trim($this->k29_saldoemitechq) == null ){ 
         $this->erro_sql = " Campo Controlar saldo da conta ao emitir cheque nao Informado.";
         $this->erro_campo = "k29_saldoemitechq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k29_datasaldocontasextra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k29_datasaldocontasextra_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k29_datasaldocontasextra_dia"] !="") ){ 
       $sql  .= $virgula." k29_datasaldocontasextra = '$this->k29_datasaldocontasextra' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k29_datasaldocontasextra_dia"])){ 
         $sql  .= $virgula." k29_datasaldocontasextra = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k29_trazdatacheque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k29_trazdatacheque"])){ 
       $sql  .= $virgula." k29_trazdatacheque = '$this->k29_trazdatacheque' ";
       $virgula = ",";
       if(trim($this->k29_trazdatacheque) == null ){ 
         $this->erro_sql = " Campo Trazer data cheques pagamentos agenda nao Informado.";
         $this->erro_campo = "k29_trazdatacheque";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k29_contassemmovimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k29_contassemmovimento"])){ 
       $sql  .= $virgula." k29_contassemmovimento = '$this->k29_contassemmovimento' ";
       $virgula = ",";
       if(trim($this->k29_contassemmovimento) == null ){ 
         $this->erro_sql = " Campo Trazer Contas sem Movimento nao Informado.";
         $this->erro_campo = "k29_contassemmovimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k29_orctiporecfundeb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k29_orctiporecfundeb"])){ 
       $sql  .= $virgula." k29_orctiporecfundeb = $this->k29_orctiporecfundeb ";
       $virgula = ",";
       if(trim($this->k29_orctiporecfundeb) == null ){ 
         $this->erro_sql = " Campo Recurso Fundeb nao Informado.";
         $this->erro_campo = "k29_orctiporecfundeb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k29_instit!=null){
       $sql .= " k29_instit = $this->k29_instit";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k29_instit));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,8803,'$this->k29_instit','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k29_instit"]) || $this->k29_instit != "")
             $resac = db_query("insert into db_acount values($acount,1503,8803,'".AddSlashes(pg_result($resaco,$conresaco,'k29_instit'))."','$this->k29_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k29_boletimzerado"]) || $this->k29_boletimzerado != "")
             $resac = db_query("insert into db_acount values($acount,1503,8802,'".AddSlashes(pg_result($resaco,$conresaco,'k29_boletimzerado'))."','$this->k29_boletimzerado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k29_modslipnormal"]) || $this->k29_modslipnormal != "")
             $resac = db_query("insert into db_acount values($acount,1503,9188,'".AddSlashes(pg_result($resaco,$conresaco,'k29_modslipnormal'))."','$this->k29_modslipnormal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k29_modsliptransf"]) || $this->k29_modsliptransf != "")
             $resac = db_query("insert into db_acount values($acount,1503,9189,'".AddSlashes(pg_result($resaco,$conresaco,'k29_modsliptransf'))."','$this->k29_modsliptransf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k29_chqduplicado"]) || $this->k29_chqduplicado != "")
             $resac = db_query("insert into db_acount values($acount,1503,9555,'".AddSlashes(pg_result($resaco,$conresaco,'k29_chqduplicado'))."','$this->k29_chqduplicado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k29_chqemitidonaoautent"]) || $this->k29_chqemitidonaoautent != "")
             $resac = db_query("insert into db_acount values($acount,1503,10933,'".AddSlashes(pg_result($resaco,$conresaco,'k29_chqemitidonaoautent'))."','$this->k29_chqemitidonaoautent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k29_saldoemitechq"]) || $this->k29_saldoemitechq != "")
             $resac = db_query("insert into db_acount values($acount,1503,10932,'".AddSlashes(pg_result($resaco,$conresaco,'k29_saldoemitechq'))."','$this->k29_saldoemitechq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k29_datasaldocontasextra"]) || $this->k29_datasaldocontasextra != "")
             $resac = db_query("insert into db_acount values($acount,1503,14540,'".AddSlashes(pg_result($resaco,$conresaco,'k29_datasaldocontasextra'))."','$this->k29_datasaldocontasextra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k29_trazdatacheque"]) || $this->k29_trazdatacheque != "")
             $resac = db_query("insert into db_acount values($acount,1503,14618,'".AddSlashes(pg_result($resaco,$conresaco,'k29_trazdatacheque'))."','$this->k29_trazdatacheque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k29_contassemmovimento"]) || $this->k29_contassemmovimento != "")
             $resac = db_query("insert into db_acount values($acount,1503,15311,'".AddSlashes(pg_result($resaco,$conresaco,'k29_contassemmovimento'))."','$this->k29_contassemmovimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k29_orctiporecfundeb"]) || $this->k29_orctiporecfundeb != "")
             $resac = db_query("insert into db_acount values($acount,1503,20050,'".AddSlashes(pg_result($resaco,$conresaco,'k29_orctiporecfundeb'))."','$this->k29_orctiporecfundeb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "k29 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k29_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "k29 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k29_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k29_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k29_instit=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($k29_instit));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,8803,'$k29_instit','E')");
           $resac  = db_query("insert into db_acount values($acount,1503,8803,'','".AddSlashes(pg_result($resaco,$iresaco,'k29_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1503,8802,'','".AddSlashes(pg_result($resaco,$iresaco,'k29_boletimzerado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1503,9188,'','".AddSlashes(pg_result($resaco,$iresaco,'k29_modslipnormal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1503,9189,'','".AddSlashes(pg_result($resaco,$iresaco,'k29_modsliptransf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1503,9555,'','".AddSlashes(pg_result($resaco,$iresaco,'k29_chqduplicado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1503,10933,'','".AddSlashes(pg_result($resaco,$iresaco,'k29_chqemitidonaoautent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1503,10932,'','".AddSlashes(pg_result($resaco,$iresaco,'k29_saldoemitechq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1503,14540,'','".AddSlashes(pg_result($resaco,$iresaco,'k29_datasaldocontasextra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1503,14618,'','".AddSlashes(pg_result($resaco,$iresaco,'k29_trazdatacheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1503,15311,'','".AddSlashes(pg_result($resaco,$iresaco,'k29_contassemmovimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1503,20050,'','".AddSlashes(pg_result($resaco,$iresaco,'k29_orctiporecfundeb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from caiparametro
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k29_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k29_instit = $k29_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "k29 nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k29_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "k29 nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k29_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k29_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:caiparametro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k29_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caiparametro ";
     $sql .= "      inner join db_config  on  db_config.codigo = caiparametro.k29_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($k29_instit!=null ){
         $sql2 .= " where caiparametro.k29_instit = $k29_instit "; 
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
   function sql_query_file ( $k29_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caiparametro ";
     $sql2 = "";
     if($dbwhere==""){
       if($k29_instit!=null ){
         $sql2 .= " where caiparametro.k29_instit = $k29_instit "; 
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