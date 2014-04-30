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

//MODULO: caixa
//CLASSE DA ENTIDADE disbanco
class cl_disbanco { 
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
   var $k00_numbco = null; 
   var $k15_codbco = 0; 
   var $k15_codage = null; 
   var $codret = 0; 
   var $dtarq_dia = null; 
   var $dtarq_mes = null; 
   var $dtarq_ano = null; 
   var $dtarq = null; 
   var $dtpago_dia = null; 
   var $dtpago_mes = null; 
   var $dtpago_ano = null; 
   var $dtpago = null; 
   var $vlrpago = 0; 
   var $vlrjuros = 0; 
   var $vlrmulta = 0; 
   var $vlracres = 0; 
   var $vlrdesco = 0; 
   var $vlrtot = 0; 
   var $cedente = null; 
   var $vlrcalc = 0; 
   var $idret = 0; 
   var $classi = 'f'; 
   var $k00_numpre = 0; 
   var $k00_numpar = 0; 
   var $convenio = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k00_numbco = varchar(15) = numero do banco 
                 k15_codbco = int4 = Banco 
                 k15_codage = char(5) = Agência 
                 codret = int4 = Código 
                 dtarq = date = Dt. Arquivo 
                 dtpago = date = Dt Pagamento 
                 vlrpago = float8 = Valor pago 
                 vlrjuros = float8 = Valor juros 
                 vlrmulta = float8 = Valor Multa 
                 vlracres = float8 = valor dos acrescimos 
                 vlrdesco = float8 = Valor desconto 
                 vlrtot = float8 = Total Pago 
                 cedente = varchar(10) = Cedente 
                 vlrcalc = float8 = valor calculado 
                 idret = int4 = Cód. Ret. 
                 classi = bool = Class 
                 k00_numpre = int4 = Numpre 
                 k00_numpar = int4 = Parcela 
                 convenio = varchar(100) = Convênio 
                 ";
   //funcao construtor da classe 
   function cl_disbanco() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("disbanco"); 
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
       $this->k00_numbco = ($this->k00_numbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numbco"]:$this->k00_numbco);
       $this->k15_codbco = ($this->k15_codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_codbco"]:$this->k15_codbco);
       $this->k15_codage = ($this->k15_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_codage"]:$this->k15_codage);
       $this->codret = ($this->codret == ""?@$GLOBALS["HTTP_POST_VARS"]["codret"]:$this->codret);
       if($this->dtarq == ""){
         $this->dtarq_dia = ($this->dtarq_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dtarq_dia"]:$this->dtarq_dia);
         $this->dtarq_mes = ($this->dtarq_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dtarq_mes"]:$this->dtarq_mes);
         $this->dtarq_ano = ($this->dtarq_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dtarq_ano"]:$this->dtarq_ano);
         if($this->dtarq_dia != ""){
            $this->dtarq = $this->dtarq_ano."-".$this->dtarq_mes."-".$this->dtarq_dia;
         }
       }
       if($this->dtpago == ""){
         $this->dtpago_dia = ($this->dtpago_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dtpago_dia"]:$this->dtpago_dia);
         $this->dtpago_mes = ($this->dtpago_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dtpago_mes"]:$this->dtpago_mes);
         $this->dtpago_ano = ($this->dtpago_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dtpago_ano"]:$this->dtpago_ano);
         if($this->dtpago_dia != ""){
            $this->dtpago = $this->dtpago_ano."-".$this->dtpago_mes."-".$this->dtpago_dia;
         }
       }
       $this->vlrpago = ($this->vlrpago == ""?@$GLOBALS["HTTP_POST_VARS"]["vlrpago"]:$this->vlrpago);
       $this->vlrjuros = ($this->vlrjuros == ""?@$GLOBALS["HTTP_POST_VARS"]["vlrjuros"]:$this->vlrjuros);
       $this->vlrmulta = ($this->vlrmulta == ""?@$GLOBALS["HTTP_POST_VARS"]["vlrmulta"]:$this->vlrmulta);
       $this->vlracres = ($this->vlracres == ""?@$GLOBALS["HTTP_POST_VARS"]["vlracres"]:$this->vlracres);
       $this->vlrdesco = ($this->vlrdesco == ""?@$GLOBALS["HTTP_POST_VARS"]["vlrdesco"]:$this->vlrdesco);
       $this->vlrtot = ($this->vlrtot == ""?@$GLOBALS["HTTP_POST_VARS"]["vlrtot"]:$this->vlrtot);
       $this->cedente = ($this->cedente == ""?@$GLOBALS["HTTP_POST_VARS"]["cedente"]:$this->cedente);
       $this->vlrcalc = ($this->vlrcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["vlrcalc"]:$this->vlrcalc);
       $this->idret = ($this->idret == ""?@$GLOBALS["HTTP_POST_VARS"]["idret"]:$this->idret);
       $this->classi = ($this->classi == "f"?@$GLOBALS["HTTP_POST_VARS"]["classi"]:$this->classi);
       $this->k00_numpre = ($this->k00_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpre"]:$this->k00_numpre);
       $this->k00_numpar = ($this->k00_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpar"]:$this->k00_numpar);
       $this->convenio = ($this->convenio == ""?@$GLOBALS["HTTP_POST_VARS"]["convenio"]:$this->convenio);
     }else{
       $this->idret = ($this->idret == ""?@$GLOBALS["HTTP_POST_VARS"]["idret"]:$this->idret);
     }
   }
   // funcao para inclusao
   function incluir ($idret){ 
      $this->atualizacampos();
     if($this->k15_codbco == null ){ 
       $this->erro_sql = " Campo Banco nao Informado.";
       $this->erro_campo = "k15_codbco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_codage == null ){ 
       $this->erro_sql = " Campo Agência nao Informado.";
       $this->erro_campo = "k15_codage";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->codret == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "codret";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dtarq == null ){ 
       $this->erro_sql = " Campo Dt. Arquivo nao Informado.";
       $this->erro_campo = "dtarq_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dtpago == null ){ 
       $this->erro_sql = " Campo Dt Pagamento nao Informado.";
       $this->erro_campo = "dtpago_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vlrpago == null ){ 
       $this->erro_sql = " Campo Valor pago nao Informado.";
       $this->erro_campo = "vlrpago";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vlrjuros == null ){ 
       $this->vlrjuros = "0";
     }
     if($this->vlrmulta == null ){ 
       $this->vlrmulta = "0";
     }
     if($this->vlracres == null ){ 
       $this->vlracres = "0";
     }
     if($this->vlrdesco == null ){ 
       $this->vlrdesco = "0";
     }
     if($this->vlrtot == null ){ 
       $this->erro_sql = " Campo Total Pago nao Informado.";
       $this->erro_campo = "vlrtot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vlrcalc == null ){ 
       $this->erro_sql = " Campo valor calculado nao Informado.";
       $this->erro_campo = "vlrcalc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->classi == null ){ 
       $this->erro_sql = " Campo Class nao Informado.";
       $this->erro_campo = "classi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "k00_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_numpar == null ){ 
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "k00_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($idret == "" || $idret == null ){
       $result = @pg_query("select nextval('disbanco_idret_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: disbanco_idret_seq do campo: idret"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->idret = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from disbanco_idret_seq");
       if(($result != false) && (pg_result($result,0,0) < $idret)){
         $this->erro_sql = " Campo idret maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->idret = $idret; 
       }
     }
     if(($this->idret == null) || ($this->idret == "") ){ 
       $this->erro_sql = " Campo idret nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into disbanco(
                                       k00_numbco 
                                      ,k15_codbco 
                                      ,k15_codage 
                                      ,codret 
                                      ,dtarq 
                                      ,dtpago 
                                      ,vlrpago 
                                      ,vlrjuros 
                                      ,vlrmulta 
                                      ,vlracres 
                                      ,vlrdesco 
                                      ,vlrtot 
                                      ,cedente 
                                      ,vlrcalc 
                                      ,idret 
                                      ,classi 
                                      ,k00_numpre 
                                      ,k00_numpar 
                                      ,convenio 
                       )
                values (
                                '$this->k00_numbco' 
                               ,$this->k15_codbco 
                               ,'$this->k15_codage' 
                               ,$this->codret 
                               ,".($this->dtarq == "null" || $this->dtarq == ""?"null":"'".$this->dtarq."'")." 
                               ,".($this->dtpago == "null" || $this->dtpago == ""?"null":"'".$this->dtpago."'")." 
                               ,$this->vlrpago 
                               ,$this->vlrjuros 
                               ,$this->vlrmulta 
                               ,$this->vlracres 
                               ,$this->vlrdesco 
                               ,$this->vlrtot 
                               ,'$this->cedente' 
                               ,$this->vlrcalc 
                               ,$this->idret 
                               ,'$this->classi' 
                               ,$this->k00_numpre 
                               ,$this->k00_numpar 
                               ,'$this->convenio' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados do Arquivo ($this->idret) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados do Arquivo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados do Arquivo ($this->idret) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->idret;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->idret));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1201,'$this->idret','I')");
       $resac = pg_query("insert into db_acount values($acount,214,365,'','".AddSlashes(pg_result($resaco,0,'k00_numbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,586,'','".AddSlashes(pg_result($resaco,0,'k15_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,587,'','".AddSlashes(pg_result($resaco,0,'k15_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,1179,'','".AddSlashes(pg_result($resaco,0,'codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,1184,'','".AddSlashes(pg_result($resaco,0,'dtarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,1185,'','".AddSlashes(pg_result($resaco,0,'dtpago'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,1186,'','".AddSlashes(pg_result($resaco,0,'vlrpago'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,1187,'','".AddSlashes(pg_result($resaco,0,'vlrjuros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,1188,'','".AddSlashes(pg_result($resaco,0,'vlrmulta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,1189,'','".AddSlashes(pg_result($resaco,0,'vlracres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,1190,'','".AddSlashes(pg_result($resaco,0,'vlrdesco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,1191,'','".AddSlashes(pg_result($resaco,0,'vlrtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,1192,'','".AddSlashes(pg_result($resaco,0,'cedente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,1199,'','".AddSlashes(pg_result($resaco,0,'vlrcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,1201,'','".AddSlashes(pg_result($resaco,0,'idret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,1903,'','".AddSlashes(pg_result($resaco,0,'classi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,361,'','".AddSlashes(pg_result($resaco,0,'k00_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,362,'','".AddSlashes(pg_result($resaco,0,'k00_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,214,2401,'','".AddSlashes(pg_result($resaco,0,'convenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($idret=null) { 
      $this->atualizacampos();
     $sql = " update disbanco set ";
     $virgula = "";
     if(trim($this->k00_numbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numbco"])){ 
       $sql  .= $virgula." k00_numbco = '$this->k00_numbco' ";
       $virgula = ",";
     }
     if(trim($this->k15_codbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_codbco"])){ 
       $sql  .= $virgula." k15_codbco = $this->k15_codbco ";
       $virgula = ",";
       if(trim($this->k15_codbco) == null ){ 
         $this->erro_sql = " Campo Banco nao Informado.";
         $this->erro_campo = "k15_codbco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_codage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_codage"])){ 
       $sql  .= $virgula." k15_codage = '$this->k15_codage' ";
       $virgula = ",";
       if(trim($this->k15_codage) == null ){ 
         $this->erro_sql = " Campo Agência nao Informado.";
         $this->erro_campo = "k15_codage";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codret"])){ 
       $sql  .= $virgula." codret = $this->codret ";
       $virgula = ",";
       if(trim($this->codret) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "codret";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dtarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dtarq_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dtarq_dia"] !="") ){ 
       $sql  .= $virgula." dtarq = '$this->dtarq' ";
       $virgula = ",";
       if(trim($this->dtarq) == null ){ 
         $this->erro_sql = " Campo Dt. Arquivo nao Informado.";
         $this->erro_campo = "dtarq_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dtarq_dia"])){ 
         $sql  .= $virgula." dtarq = null ";
         $virgula = ",";
         if(trim($this->dtarq) == null ){ 
           $this->erro_sql = " Campo Dt. Arquivo nao Informado.";
           $this->erro_campo = "dtarq_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->dtpago)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dtpago_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dtpago_dia"] !="") ){ 
       $sql  .= $virgula." dtpago = '$this->dtpago' ";
       $virgula = ",";
       if(trim($this->dtpago) == null ){ 
         $this->erro_sql = " Campo Dt Pagamento nao Informado.";
         $this->erro_campo = "dtpago_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dtpago_dia"])){ 
         $sql  .= $virgula." dtpago = null ";
         $virgula = ",";
         if(trim($this->dtpago) == null ){ 
           $this->erro_sql = " Campo Dt Pagamento nao Informado.";
           $this->erro_campo = "dtpago_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->vlrpago)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vlrpago"])){ 
       $sql  .= $virgula." vlrpago = $this->vlrpago ";
       $virgula = ",";
       if(trim($this->vlrpago) == null ){ 
         $this->erro_sql = " Campo Valor pago nao Informado.";
         $this->erro_campo = "vlrpago";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vlrjuros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vlrjuros"])){ 
        if(trim($this->vlrjuros)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vlrjuros"])){ 
           $this->vlrjuros = "0" ; 
        } 
       $sql  .= $virgula." vlrjuros = $this->vlrjuros ";
       $virgula = ",";
     }
     if(trim($this->vlrmulta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vlrmulta"])){ 
        if(trim($this->vlrmulta)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vlrmulta"])){ 
           $this->vlrmulta = "0" ; 
        } 
       $sql  .= $virgula." vlrmulta = $this->vlrmulta ";
       $virgula = ",";
     }
     if(trim($this->vlracres)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vlracres"])){ 
        if(trim($this->vlracres)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vlracres"])){ 
           $this->vlracres = "0" ; 
        } 
       $sql  .= $virgula." vlracres = $this->vlracres ";
       $virgula = ",";
     }
     if(trim($this->vlrdesco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vlrdesco"])){ 
        if(trim($this->vlrdesco)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vlrdesco"])){ 
           $this->vlrdesco = "0" ; 
        } 
       $sql  .= $virgula." vlrdesco = $this->vlrdesco ";
       $virgula = ",";
     }
     if(trim($this->vlrtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vlrtot"])){ 
       $sql  .= $virgula." vlrtot = $this->vlrtot ";
       $virgula = ",";
       if(trim($this->vlrtot) == null ){ 
         $this->erro_sql = " Campo Total Pago nao Informado.";
         $this->erro_campo = "vlrtot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cedente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cedente"])){ 
       $sql  .= $virgula." cedente = '$this->cedente' ";
       $virgula = ",";
     }
     if(trim($this->vlrcalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vlrcalc"])){ 
       $sql  .= $virgula." vlrcalc = $this->vlrcalc ";
       $virgula = ",";
       if(trim($this->vlrcalc) == null ){ 
         $this->erro_sql = " Campo valor calculado nao Informado.";
         $this->erro_campo = "vlrcalc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->idret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["idret"])){ 
       $sql  .= $virgula." idret = $this->idret ";
       $virgula = ",";
       if(trim($this->idret) == null ){ 
         $this->erro_sql = " Campo Cód. Ret. nao Informado.";
         $this->erro_campo = "idret";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->classi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["classi"])){ 
       $sql  .= $virgula." classi = '$this->classi' ";
       $virgula = ",";
       if(trim($this->classi) == null ){ 
         $this->erro_sql = " Campo Class nao Informado.";
         $this->erro_campo = "classi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numpre"])){ 
       $sql  .= $virgula." k00_numpre = $this->k00_numpre ";
       $virgula = ",";
       if(trim($this->k00_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k00_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numpar"])){ 
       $sql  .= $virgula." k00_numpar = $this->k00_numpar ";
       $virgula = ",";
       if(trim($this->k00_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "k00_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->convenio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["convenio"])){ 
       $sql  .= $virgula." convenio = '$this->convenio' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($idret!=null){
       $sql .= " idret = $this->idret";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->idret));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1201,'$this->idret','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numbco"]))
           $resac = pg_query("insert into db_acount values($acount,214,365,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numbco'))."','$this->k00_numbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k15_codbco"]))
           $resac = pg_query("insert into db_acount values($acount,214,586,'".AddSlashes(pg_result($resaco,$conresaco,'k15_codbco'))."','$this->k15_codbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k15_codage"]))
           $resac = pg_query("insert into db_acount values($acount,214,587,'".AddSlashes(pg_result($resaco,$conresaco,'k15_codage'))."','$this->k15_codage',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codret"]))
           $resac = pg_query("insert into db_acount values($acount,214,1179,'".AddSlashes(pg_result($resaco,$conresaco,'codret'))."','$this->codret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dtarq"]))
           $resac = pg_query("insert into db_acount values($acount,214,1184,'".AddSlashes(pg_result($resaco,$conresaco,'dtarq'))."','$this->dtarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dtpago"]))
           $resac = pg_query("insert into db_acount values($acount,214,1185,'".AddSlashes(pg_result($resaco,$conresaco,'dtpago'))."','$this->dtpago',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vlrpago"]))
           $resac = pg_query("insert into db_acount values($acount,214,1186,'".AddSlashes(pg_result($resaco,$conresaco,'vlrpago'))."','$this->vlrpago',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vlrjuros"]))
           $resac = pg_query("insert into db_acount values($acount,214,1187,'".AddSlashes(pg_result($resaco,$conresaco,'vlrjuros'))."','$this->vlrjuros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vlrmulta"]))
           $resac = pg_query("insert into db_acount values($acount,214,1188,'".AddSlashes(pg_result($resaco,$conresaco,'vlrmulta'))."','$this->vlrmulta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vlracres"]))
           $resac = pg_query("insert into db_acount values($acount,214,1189,'".AddSlashes(pg_result($resaco,$conresaco,'vlracres'))."','$this->vlracres',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vlrdesco"]))
           $resac = pg_query("insert into db_acount values($acount,214,1190,'".AddSlashes(pg_result($resaco,$conresaco,'vlrdesco'))."','$this->vlrdesco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vlrtot"]))
           $resac = pg_query("insert into db_acount values($acount,214,1191,'".AddSlashes(pg_result($resaco,$conresaco,'vlrtot'))."','$this->vlrtot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cedente"]))
           $resac = pg_query("insert into db_acount values($acount,214,1192,'".AddSlashes(pg_result($resaco,$conresaco,'cedente'))."','$this->cedente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vlrcalc"]))
           $resac = pg_query("insert into db_acount values($acount,214,1199,'".AddSlashes(pg_result($resaco,$conresaco,'vlrcalc'))."','$this->vlrcalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["idret"]))
           $resac = pg_query("insert into db_acount values($acount,214,1201,'".AddSlashes(pg_result($resaco,$conresaco,'idret'))."','$this->idret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["classi"]))
           $resac = pg_query("insert into db_acount values($acount,214,1903,'".AddSlashes(pg_result($resaco,$conresaco,'classi'))."','$this->classi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numpre"]))
           $resac = pg_query("insert into db_acount values($acount,214,361,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numpre'))."','$this->k00_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numpar"]))
           $resac = pg_query("insert into db_acount values($acount,214,362,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numpar'))."','$this->k00_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["convenio"]))
           $resac = pg_query("insert into db_acount values($acount,214,2401,'".AddSlashes(pg_result($resaco,$conresaco,'convenio'))."','$this->convenio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados do Arquivo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->idret;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados do Arquivo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->idret;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->idret;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($idret=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($idret));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1201,'$idret','E')");
         $resac = pg_query("insert into db_acount values($acount,214,365,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,586,'','".AddSlashes(pg_result($resaco,$iresaco,'k15_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,587,'','".AddSlashes(pg_result($resaco,$iresaco,'k15_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,1179,'','".AddSlashes(pg_result($resaco,$iresaco,'codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,1184,'','".AddSlashes(pg_result($resaco,$iresaco,'dtarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,1185,'','".AddSlashes(pg_result($resaco,$iresaco,'dtpago'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,1186,'','".AddSlashes(pg_result($resaco,$iresaco,'vlrpago'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,1187,'','".AddSlashes(pg_result($resaco,$iresaco,'vlrjuros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,1188,'','".AddSlashes(pg_result($resaco,$iresaco,'vlrmulta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,1189,'','".AddSlashes(pg_result($resaco,$iresaco,'vlracres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,1190,'','".AddSlashes(pg_result($resaco,$iresaco,'vlrdesco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,1191,'','".AddSlashes(pg_result($resaco,$iresaco,'vlrtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,1192,'','".AddSlashes(pg_result($resaco,$iresaco,'cedente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,1199,'','".AddSlashes(pg_result($resaco,$iresaco,'vlrcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,1201,'','".AddSlashes(pg_result($resaco,$iresaco,'idret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,1903,'','".AddSlashes(pg_result($resaco,$iresaco,'classi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,361,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,362,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,214,2401,'','".AddSlashes(pg_result($resaco,$iresaco,'convenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from disbanco
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($idret != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " idret = $idret ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados do Arquivo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$idret;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados do Arquivo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$idret;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$idret;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:disbanco";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $idret=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from disbanco ";
     $sql .= "      inner join disarq  on  disarq.codret = disbanco.codret";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = disarq.id_usuario";
     $sql .= "      inner join saltes  on  saltes.k13_conta = disarq.k00_conta";
     $sql2 = "";
     if($dbwhere==""){
       if($idret!=null ){
         $sql2 .= " where disbanco.idret = $idret "; 
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
   function sql_query_file ( $idret=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from disbanco ";
     $sql2 = "";
     if($dbwhere==""){
       if($idret!=null ){
         $sql2 .= " where disbanco.idret = $idret "; 
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