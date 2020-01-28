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
//CLASSE DA ENTIDADE arreoldcalc
class cl_arreoldcalc { 
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
   var $k00_arreoldcalc = 0; 
   var $k00_numpre = 0; 
   var $k00_numpar = 0; 
   var $k00_receit = 0; 
   var $k00_hist = 0; 
   var $k00_dtcalc_dia = null; 
   var $k00_dtcalc_mes = null; 
   var $k00_dtcalc_ano = null; 
   var $k00_dtcalc = null; 
   var $k00_vlrcor = 0; 
   var $k00_vlrjur = 0; 
   var $k00_vlrmul = 0; 
   var $k00_vlrdes = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k00_arreoldcalc = int4 = Sequencial 
                 k00_numpre = int4 = Numpre 
                 k00_numpar = int4 = Parcela 
                 k00_receit = int4 = Receita 
                 k00_hist = int4 = Histórico de Cálculo 
                 k00_dtcalc = date = Data Cálculo 
                 k00_vlrcor = float8 = Valor Corrigido 
                 k00_vlrjur = float8 = Valor Juros 
                 k00_vlrmul = float8 = Valor Multa 
                 k00_vlrdes = float8 = Valor do desconto 
                 ";
   //funcao construtor da classe 
   function cl_arreoldcalc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arreoldcalc"); 
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
       $this->k00_arreoldcalc = ($this->k00_arreoldcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_arreoldcalc"]:$this->k00_arreoldcalc);
       $this->k00_numpre = ($this->k00_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpre"]:$this->k00_numpre);
       $this->k00_numpar = ($this->k00_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpar"]:$this->k00_numpar);
       $this->k00_receit = ($this->k00_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_receit"]:$this->k00_receit);
       $this->k00_hist = ($this->k00_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist"]:$this->k00_hist);
       if($this->k00_dtcalc == ""){
         $this->k00_dtcalc_dia = ($this->k00_dtcalc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtcalc_dia"]:$this->k00_dtcalc_dia);
         $this->k00_dtcalc_mes = ($this->k00_dtcalc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtcalc_mes"]:$this->k00_dtcalc_mes);
         $this->k00_dtcalc_ano = ($this->k00_dtcalc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtcalc_ano"]:$this->k00_dtcalc_ano);
         if($this->k00_dtcalc_dia != ""){
            $this->k00_dtcalc = $this->k00_dtcalc_ano."-".$this->k00_dtcalc_mes."-".$this->k00_dtcalc_dia;
         }
       }
       $this->k00_vlrcor = ($this->k00_vlrcor == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_vlrcor"]:$this->k00_vlrcor);
       $this->k00_vlrjur = ($this->k00_vlrjur == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_vlrjur"]:$this->k00_vlrjur);
       $this->k00_vlrmul = ($this->k00_vlrmul == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_vlrmul"]:$this->k00_vlrmul);
       $this->k00_vlrdes = ($this->k00_vlrdes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_vlrdes"]:$this->k00_vlrdes);
     }else{
       $this->k00_arreoldcalc = ($this->k00_arreoldcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_arreoldcalc"]:$this->k00_arreoldcalc);
     }
   }
   // funcao para inclusao
   function incluir ($k00_arreoldcalc){ 
      $this->atualizacampos();
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
     if($this->k00_receit == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "k00_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_hist == null ){ 
       $this->erro_sql = " Campo Histórico de Cálculo nao Informado.";
       $this->erro_campo = "k00_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_dtcalc == null ){ 
       $this->erro_sql = " Campo Data Cálculo nao Informado.";
       $this->erro_campo = "k00_dtcalc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_vlrcor == null ){ 
       $this->erro_sql = " Campo Valor Corrigido nao Informado.";
       $this->erro_campo = "k00_vlrcor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_vlrjur == null ){ 
       $this->erro_sql = " Campo Valor Juros nao Informado.";
       $this->erro_campo = "k00_vlrjur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_vlrmul == null ){ 
       $this->erro_sql = " Campo Valor Multa nao Informado.";
       $this->erro_campo = "k00_vlrmul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_vlrdes == null ){ 
       $this->erro_sql = " Campo Valor do desconto nao Informado.";
       $this->erro_campo = "k00_vlrdes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k00_arreoldcalc == "" || $k00_arreoldcalc == null ){
       $result = db_query("select nextval('arreoldcalc_k00_arreoldcalc_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: arreoldcalc_k00_arreoldcalc_seq do campo: k00_arreoldcalc"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k00_arreoldcalc = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from arreoldcalc_k00_arreoldcalc_seq");
       if(($result != false) && (pg_result($result,0,0) < $k00_arreoldcalc)){
         $this->erro_sql = " Campo k00_arreoldcalc maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k00_arreoldcalc = $k00_arreoldcalc; 
       }
     }
     if(($this->k00_arreoldcalc == null) || ($this->k00_arreoldcalc == "") ){ 
       $this->erro_sql = " Campo k00_arreoldcalc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arreoldcalc(
                                       k00_arreoldcalc 
                                      ,k00_numpre 
                                      ,k00_numpar 
                                      ,k00_receit 
                                      ,k00_hist 
                                      ,k00_dtcalc 
                                      ,k00_vlrcor 
                                      ,k00_vlrjur 
                                      ,k00_vlrmul 
                                      ,k00_vlrdes 
                       )
                values (
                                $this->k00_arreoldcalc 
                               ,$this->k00_numpre 
                               ,$this->k00_numpar 
                               ,$this->k00_receit 
                               ,$this->k00_hist 
                               ,".($this->k00_dtcalc == "null" || $this->k00_dtcalc == ""?"null":"'".$this->k00_dtcalc."'")." 
                               ,$this->k00_vlrcor 
                               ,$this->k00_vlrjur 
                               ,$this->k00_vlrmul 
                               ,$this->k00_vlrdes 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "arreoldcalc ($this->k00_arreoldcalc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "arreoldcalc já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "arreoldcalc ($this->k00_arreoldcalc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_arreoldcalc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k00_arreoldcalc));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10024,'$this->k00_arreoldcalc','I')");
       $resac = db_query("insert into db_acount values($acount,1721,10024,'','".AddSlashes(pg_result($resaco,0,'k00_arreoldcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1721,361,'','".AddSlashes(pg_result($resaco,0,'k00_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1721,362,'','".AddSlashes(pg_result($resaco,0,'k00_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1721,374,'','".AddSlashes(pg_result($resaco,0,'k00_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1721,375,'','".AddSlashes(pg_result($resaco,0,'k00_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1721,10023,'','".AddSlashes(pg_result($resaco,0,'k00_dtcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1721,10020,'','".AddSlashes(pg_result($resaco,0,'k00_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1721,10021,'','".AddSlashes(pg_result($resaco,0,'k00_vlrjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1721,10022,'','".AddSlashes(pg_result($resaco,0,'k00_vlrmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1721,10025,'','".AddSlashes(pg_result($resaco,0,'k00_vlrdes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k00_arreoldcalc=null) { 
      $this->atualizacampos();
     $sql = " update arreoldcalc set ";
     $virgula = "";
     if(trim($this->k00_arreoldcalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_arreoldcalc"])){ 
       $sql  .= $virgula." k00_arreoldcalc = $this->k00_arreoldcalc ";
       $virgula = ",";
       if(trim($this->k00_arreoldcalc) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k00_arreoldcalc";
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
     if(trim($this->k00_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_receit"])){ 
       $sql  .= $virgula." k00_receit = $this->k00_receit ";
       $virgula = ",";
       if(trim($this->k00_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "k00_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist"])){ 
       $sql  .= $virgula." k00_hist = $this->k00_hist ";
       $virgula = ",";
       if(trim($this->k00_hist) == null ){ 
         $this->erro_sql = " Campo Histórico de Cálculo nao Informado.";
         $this->erro_campo = "k00_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_dtcalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtcalc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtcalc_dia"] !="") ){ 
       $sql  .= $virgula." k00_dtcalc = '$this->k00_dtcalc' ";
       $virgula = ",";
       if(trim($this->k00_dtcalc) == null ){ 
         $this->erro_sql = " Campo Data Cálculo nao Informado.";
         $this->erro_campo = "k00_dtcalc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtcalc_dia"])){ 
         $sql  .= $virgula." k00_dtcalc = null ";
         $virgula = ",";
         if(trim($this->k00_dtcalc) == null ){ 
           $this->erro_sql = " Campo Data Cálculo nao Informado.";
           $this->erro_campo = "k00_dtcalc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k00_vlrcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrcor"])){ 
       $sql  .= $virgula." k00_vlrcor = $this->k00_vlrcor ";
       $virgula = ",";
       if(trim($this->k00_vlrcor) == null ){ 
         $this->erro_sql = " Campo Valor Corrigido nao Informado.";
         $this->erro_campo = "k00_vlrcor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_vlrjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrjur"])){ 
       $sql  .= $virgula." k00_vlrjur = $this->k00_vlrjur ";
       $virgula = ",";
       if(trim($this->k00_vlrjur) == null ){ 
         $this->erro_sql = " Campo Valor Juros nao Informado.";
         $this->erro_campo = "k00_vlrjur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_vlrmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrmul"])){ 
       $sql  .= $virgula." k00_vlrmul = $this->k00_vlrmul ";
       $virgula = ",";
       if(trim($this->k00_vlrmul) == null ){ 
         $this->erro_sql = " Campo Valor Multa nao Informado.";
         $this->erro_campo = "k00_vlrmul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_vlrdes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrdes"])){ 
       $sql  .= $virgula." k00_vlrdes = $this->k00_vlrdes ";
       $virgula = ",";
       if(trim($this->k00_vlrdes) == null ){ 
         $this->erro_sql = " Campo Valor do desconto nao Informado.";
         $this->erro_campo = "k00_vlrdes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k00_arreoldcalc!=null){
       $sql .= " k00_arreoldcalc = $this->k00_arreoldcalc";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k00_arreoldcalc));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10024,'$this->k00_arreoldcalc','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_arreoldcalc"]))
           $resac = db_query("insert into db_acount values($acount,1721,10024,'".AddSlashes(pg_result($resaco,$conresaco,'k00_arreoldcalc'))."','$this->k00_arreoldcalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numpre"]))
           $resac = db_query("insert into db_acount values($acount,1721,361,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numpre'))."','$this->k00_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numpar"]))
           $resac = db_query("insert into db_acount values($acount,1721,362,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numpar'))."','$this->k00_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_receit"]))
           $resac = db_query("insert into db_acount values($acount,1721,374,'".AddSlashes(pg_result($resaco,$conresaco,'k00_receit'))."','$this->k00_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_hist"]))
           $resac = db_query("insert into db_acount values($acount,1721,375,'".AddSlashes(pg_result($resaco,$conresaco,'k00_hist'))."','$this->k00_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtcalc"]))
           $resac = db_query("insert into db_acount values($acount,1721,10023,'".AddSlashes(pg_result($resaco,$conresaco,'k00_dtcalc'))."','$this->k00_dtcalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrcor"]))
           $resac = db_query("insert into db_acount values($acount,1721,10020,'".AddSlashes(pg_result($resaco,$conresaco,'k00_vlrcor'))."','$this->k00_vlrcor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrjur"]))
           $resac = db_query("insert into db_acount values($acount,1721,10021,'".AddSlashes(pg_result($resaco,$conresaco,'k00_vlrjur'))."','$this->k00_vlrjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrmul"]))
           $resac = db_query("insert into db_acount values($acount,1721,10022,'".AddSlashes(pg_result($resaco,$conresaco,'k00_vlrmul'))."','$this->k00_vlrmul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrdes"]))
           $resac = db_query("insert into db_acount values($acount,1721,10025,'".AddSlashes(pg_result($resaco,$conresaco,'k00_vlrdes'))."','$this->k00_vlrdes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arreoldcalc nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_arreoldcalc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arreoldcalc nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_arreoldcalc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_arreoldcalc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k00_arreoldcalc=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k00_arreoldcalc));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10024,'$k00_arreoldcalc','E')");
         $resac = db_query("insert into db_acount values($acount,1721,10024,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_arreoldcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1721,361,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1721,362,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1721,374,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1721,375,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1721,10023,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_dtcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1721,10020,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1721,10021,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_vlrjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1721,10022,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_vlrmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1721,10025,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_vlrdes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from arreoldcalc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k00_arreoldcalc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k00_arreoldcalc = $k00_arreoldcalc ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arreoldcalc nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k00_arreoldcalc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arreoldcalc nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k00_arreoldcalc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k00_arreoldcalc;
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
        $this->erro_sql   = "Record Vazio na Tabela:arreoldcalc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>