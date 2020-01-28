<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE arresusp
class cl_arresusp { 
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
   var $k00_sequencial = 0; 
   var $k00_suspensao = 0; 
   var $k00_numpre = 0; 
   var $k00_numpar = 0; 
   var $k00_numcgm = 0; 
   var $k00_dtoper_dia = null; 
   var $k00_dtoper_mes = null; 
   var $k00_dtoper_ano = null; 
   var $k00_dtoper = null; 
   var $k00_receit = 0; 
   var $k00_hist = 0; 
   var $k00_valor = 0; 
   var $k00_dtvenc_dia = null; 
   var $k00_dtvenc_mes = null; 
   var $k00_dtvenc_ano = null; 
   var $k00_dtvenc = null; 
   var $k00_numtot = 0; 
   var $k00_numdig = 0; 
   var $k00_tipo = 0; 
   var $k00_tipojm = 0; 
   var $k00_vlrcor = 0; 
   var $k00_vlrjur = 0; 
   var $k00_vlrmul = 0; 
   var $k00_vlrdes = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k00_sequencial = int4 = Código da arrecadação suspensa 
                 k00_suspensao = int4 = Suspensão 
                 k00_numpre = int4 = Numpre 
                 k00_numpar = int4 = Parcela 
                 k00_numcgm = int4 = cgm 
                 k00_dtoper = date = DT.Lanc 
                 k00_receit = int4 = Receita 
                 k00_hist = int4 = Histórico de Cálculo 
                 k00_valor = float8 = Valor 
                 k00_dtvenc = date = DT.Venc 
                 k00_numtot = int4 = Tot 
                 k00_numdig = int4 = D 
                 k00_tipo = int4 = Tipo de Débito 
                 k00_tipojm = int4 = tipo de juro e multa 
                 k00_vlrcor = float8 = Valor Corrigido 
                 k00_vlrjur = float8 = Valor Juros 
                 k00_vlrmul = float8 = Valor Multa 
                 k00_vlrdes = float8 = Valor do desconto 
                 ";
   //funcao construtor da classe 
   function cl_arresusp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arresusp"); 
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
       $this->k00_sequencial = ($this->k00_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_sequencial"]:$this->k00_sequencial);
       $this->k00_suspensao = ($this->k00_suspensao == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_suspensao"]:$this->k00_suspensao);
       $this->k00_numpre = ($this->k00_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpre"]:$this->k00_numpre);
       $this->k00_numpar = ($this->k00_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpar"]:$this->k00_numpar);
       $this->k00_numcgm = ($this->k00_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numcgm"]:$this->k00_numcgm);
       if($this->k00_dtoper == ""){
         $this->k00_dtoper_dia = ($this->k00_dtoper_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"]:$this->k00_dtoper_dia);
         $this->k00_dtoper_mes = ($this->k00_dtoper_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtoper_mes"]:$this->k00_dtoper_mes);
         $this->k00_dtoper_ano = ($this->k00_dtoper_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtoper_ano"]:$this->k00_dtoper_ano);
         if($this->k00_dtoper_dia != ""){
            $this->k00_dtoper = $this->k00_dtoper_ano."-".$this->k00_dtoper_mes."-".$this->k00_dtoper_dia;
         }
       }
       $this->k00_receit = ($this->k00_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_receit"]:$this->k00_receit);
       $this->k00_hist = ($this->k00_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist"]:$this->k00_hist);
       $this->k00_valor = ($this->k00_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_valor"]:$this->k00_valor);
       if($this->k00_dtvenc == ""){
         $this->k00_dtvenc_dia = ($this->k00_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"]:$this->k00_dtvenc_dia);
         $this->k00_dtvenc_mes = ($this->k00_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_mes"]:$this->k00_dtvenc_mes);
         $this->k00_dtvenc_ano = ($this->k00_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_ano"]:$this->k00_dtvenc_ano);
         if($this->k00_dtvenc_dia != ""){
            $this->k00_dtvenc = $this->k00_dtvenc_ano."-".$this->k00_dtvenc_mes."-".$this->k00_dtvenc_dia;
         }
       }
       $this->k00_numtot = ($this->k00_numtot == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numtot"]:$this->k00_numtot);
       $this->k00_numdig = ($this->k00_numdig == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numdig"]:$this->k00_numdig);
       $this->k00_tipo = ($this->k00_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_tipo"]:$this->k00_tipo);
       $this->k00_tipojm = ($this->k00_tipojm == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_tipojm"]:$this->k00_tipojm);
       $this->k00_vlrcor = ($this->k00_vlrcor == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_vlrcor"]:$this->k00_vlrcor);
       $this->k00_vlrjur = ($this->k00_vlrjur == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_vlrjur"]:$this->k00_vlrjur);
       $this->k00_vlrmul = ($this->k00_vlrmul == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_vlrmul"]:$this->k00_vlrmul);
       $this->k00_vlrdes = ($this->k00_vlrdes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_vlrdes"]:$this->k00_vlrdes);
     }else{
       $this->k00_sequencial = ($this->k00_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_sequencial"]:$this->k00_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k00_sequencial){ 
      $this->atualizacampos();
     if($this->k00_suspensao == null ){ 
       $this->erro_sql = " Campo Suspensão nao Informado.";
       $this->erro_campo = "k00_suspensao";
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
     if($this->k00_numcgm == null ){ 
       $this->erro_sql = " Campo cgm nao Informado.";
       $this->erro_campo = "k00_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_dtoper == null ){ 
       $this->erro_sql = " Campo DT.Lanc nao Informado.";
       $this->erro_campo = "k00_dtoper_dia";
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
     if($this->k00_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "k00_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_dtvenc == null ){ 
       $this->erro_sql = " Campo DT.Venc nao Informado.";
       $this->erro_campo = "k00_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_numtot == null ){ 
       $this->erro_sql = " Campo Tot nao Informado.";
       $this->erro_campo = "k00_numtot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_numdig == null ){ 
       $this->k00_numdig = "0";
     }
     if($this->k00_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Débito nao Informado.";
       $this->erro_campo = "k00_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_tipojm == null ){ 
       $this->erro_sql = " Campo tipo de juro e multa nao Informado.";
       $this->erro_campo = "k00_tipojm";
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
     if($k00_sequencial == "" || $k00_sequencial == null ){
       $result = db_query("select nextval('arresusp_k00_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: arresusp_k00_sequencial_seq do campo: k00_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k00_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from arresusp_k00_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k00_sequencial)){
         $this->erro_sql = " Campo k00_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k00_sequencial = $k00_sequencial; 
       }
     }
     if(($this->k00_sequencial == null) || ($this->k00_sequencial == "") ){ 
       $this->erro_sql = " Campo k00_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arresusp(
                                       k00_sequencial 
                                      ,k00_suspensao 
                                      ,k00_numpre 
                                      ,k00_numpar 
                                      ,k00_numcgm 
                                      ,k00_dtoper 
                                      ,k00_receit 
                                      ,k00_hist 
                                      ,k00_valor 
                                      ,k00_dtvenc 
                                      ,k00_numtot 
                                      ,k00_numdig 
                                      ,k00_tipo 
                                      ,k00_tipojm 
                                      ,k00_vlrcor 
                                      ,k00_vlrjur 
                                      ,k00_vlrmul 
                                      ,k00_vlrdes 
                       )
                values (
                                $this->k00_sequencial 
                               ,$this->k00_suspensao 
                               ,$this->k00_numpre 
                               ,$this->k00_numpar 
                               ,$this->k00_numcgm 
                               ,".($this->k00_dtoper == "null" || $this->k00_dtoper == ""?"null":"'".$this->k00_dtoper."'")." 
                               ,$this->k00_receit 
                               ,$this->k00_hist 
                               ,$this->k00_valor 
                               ,".($this->k00_dtvenc == "null" || $this->k00_dtvenc == ""?"null":"'".$this->k00_dtvenc."'")." 
                               ,$this->k00_numtot 
                               ,$this->k00_numdig 
                               ,$this->k00_tipo 
                               ,$this->k00_tipojm 
                               ,$this->k00_vlrcor 
                               ,$this->k00_vlrjur 
                               ,$this->k00_vlrmul 
                               ,$this->k00_vlrdes 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "arresusp ($this->k00_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "arresusp já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "arresusp ($this->k00_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k00_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11816,'$this->k00_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2220,11816,'','".AddSlashes(pg_result($resaco,0,'k00_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,12707,'','".AddSlashes(pg_result($resaco,0,'k00_suspensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,361,'','".AddSlashes(pg_result($resaco,0,'k00_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,362,'','".AddSlashes(pg_result($resaco,0,'k00_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,372,'','".AddSlashes(pg_result($resaco,0,'k00_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,373,'','".AddSlashes(pg_result($resaco,0,'k00_dtoper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,374,'','".AddSlashes(pg_result($resaco,0,'k00_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,375,'','".AddSlashes(pg_result($resaco,0,'k00_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,376,'','".AddSlashes(pg_result($resaco,0,'k00_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,377,'','".AddSlashes(pg_result($resaco,0,'k00_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,378,'','".AddSlashes(pg_result($resaco,0,'k00_numtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,379,'','".AddSlashes(pg_result($resaco,0,'k00_numdig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,380,'','".AddSlashes(pg_result($resaco,0,'k00_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,381,'','".AddSlashes(pg_result($resaco,0,'k00_tipojm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,10020,'','".AddSlashes(pg_result($resaco,0,'k00_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,10021,'','".AddSlashes(pg_result($resaco,0,'k00_vlrjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,10022,'','".AddSlashes(pg_result($resaco,0,'k00_vlrmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2220,10025,'','".AddSlashes(pg_result($resaco,0,'k00_vlrdes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k00_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update arresusp set ";
     $virgula = "";
     if(trim($this->k00_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_sequencial"])){ 
       $sql  .= $virgula." k00_sequencial = $this->k00_sequencial ";
       $virgula = ",";
       if(trim($this->k00_sequencial) == null ){ 
         $this->erro_sql = " Campo Código da arrecadação suspensa nao Informado.";
         $this->erro_campo = "k00_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_suspensao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_suspensao"])){ 
       $sql  .= $virgula." k00_suspensao = $this->k00_suspensao ";
       $virgula = ",";
       if(trim($this->k00_suspensao) == null ){ 
         $this->erro_sql = " Campo Suspensão nao Informado.";
         $this->erro_campo = "k00_suspensao";
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
     if(trim($this->k00_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numcgm"])){ 
       $sql  .= $virgula." k00_numcgm = $this->k00_numcgm ";
       $virgula = ",";
       if(trim($this->k00_numcgm) == null ){ 
         $this->erro_sql = " Campo cgm nao Informado.";
         $this->erro_campo = "k00_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_dtoper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"] !="") ){ 
       $sql  .= $virgula." k00_dtoper = '$this->k00_dtoper' ";
       $virgula = ",";
       if(trim($this->k00_dtoper) == null ){ 
         $this->erro_sql = " Campo DT.Lanc nao Informado.";
         $this->erro_campo = "k00_dtoper_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"])){ 
         $sql  .= $virgula." k00_dtoper = null ";
         $virgula = ",";
         if(trim($this->k00_dtoper) == null ){ 
           $this->erro_sql = " Campo DT.Lanc nao Informado.";
           $this->erro_campo = "k00_dtoper_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
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
     if(trim($this->k00_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_valor"])){ 
       $sql  .= $virgula." k00_valor = $this->k00_valor ";
       $virgula = ",";
       if(trim($this->k00_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "k00_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." k00_dtvenc = '$this->k00_dtvenc' ";
       $virgula = ",";
       if(trim($this->k00_dtvenc) == null ){ 
         $this->erro_sql = " Campo DT.Venc nao Informado.";
         $this->erro_campo = "k00_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"])){ 
         $sql  .= $virgula." k00_dtvenc = null ";
         $virgula = ",";
         if(trim($this->k00_dtvenc) == null ){ 
           $this->erro_sql = " Campo DT.Venc nao Informado.";
           $this->erro_campo = "k00_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k00_numtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numtot"])){ 
       $sql  .= $virgula." k00_numtot = $this->k00_numtot ";
       $virgula = ",";
       if(trim($this->k00_numtot) == null ){ 
         $this->erro_sql = " Campo Tot nao Informado.";
         $this->erro_campo = "k00_numtot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numdig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numdig"])){ 
        if(trim($this->k00_numdig)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k00_numdig"])){ 
           $this->k00_numdig = "0" ; 
        } 
       $sql  .= $virgula." k00_numdig = $this->k00_numdig ";
       $virgula = ",";
     }
     if(trim($this->k00_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_tipo"])){ 
       $sql  .= $virgula." k00_tipo = $this->k00_tipo ";
       $virgula = ",";
       if(trim($this->k00_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Débito nao Informado.";
         $this->erro_campo = "k00_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_tipojm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_tipojm"])){ 
       $sql  .= $virgula." k00_tipojm = $this->k00_tipojm ";
       $virgula = ",";
       if(trim($this->k00_tipojm) == null ){ 
         $this->erro_sql = " Campo tipo de juro e multa nao Informado.";
         $this->erro_campo = "k00_tipojm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
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
     if($k00_sequencial!=null){
       $sql .= " k00_sequencial = $this->k00_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k00_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11816,'$this->k00_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2220,11816,'".AddSlashes(pg_result($resaco,$conresaco,'k00_sequencial'))."','$this->k00_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_suspensao"]))
           $resac = db_query("insert into db_acount values($acount,2220,12707,'".AddSlashes(pg_result($resaco,$conresaco,'k00_suspensao'))."','$this->k00_suspensao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numpre"]))
           $resac = db_query("insert into db_acount values($acount,2220,361,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numpre'))."','$this->k00_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numpar"]))
           $resac = db_query("insert into db_acount values($acount,2220,362,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numpar'))."','$this->k00_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,2220,372,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numcgm'))."','$this->k00_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtoper"]))
           $resac = db_query("insert into db_acount values($acount,2220,373,'".AddSlashes(pg_result($resaco,$conresaco,'k00_dtoper'))."','$this->k00_dtoper',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_receit"]))
           $resac = db_query("insert into db_acount values($acount,2220,374,'".AddSlashes(pg_result($resaco,$conresaco,'k00_receit'))."','$this->k00_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_hist"]))
           $resac = db_query("insert into db_acount values($acount,2220,375,'".AddSlashes(pg_result($resaco,$conresaco,'k00_hist'))."','$this->k00_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_valor"]))
           $resac = db_query("insert into db_acount values($acount,2220,376,'".AddSlashes(pg_result($resaco,$conresaco,'k00_valor'))."','$this->k00_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc"]))
           $resac = db_query("insert into db_acount values($acount,2220,377,'".AddSlashes(pg_result($resaco,$conresaco,'k00_dtvenc'))."','$this->k00_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numtot"]))
           $resac = db_query("insert into db_acount values($acount,2220,378,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numtot'))."','$this->k00_numtot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numdig"]))
           $resac = db_query("insert into db_acount values($acount,2220,379,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numdig'))."','$this->k00_numdig',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_tipo"]))
           $resac = db_query("insert into db_acount values($acount,2220,380,'".AddSlashes(pg_result($resaco,$conresaco,'k00_tipo'))."','$this->k00_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_tipojm"]))
           $resac = db_query("insert into db_acount values($acount,2220,381,'".AddSlashes(pg_result($resaco,$conresaco,'k00_tipojm'))."','$this->k00_tipojm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrcor"]))
           $resac = db_query("insert into db_acount values($acount,2220,10020,'".AddSlashes(pg_result($resaco,$conresaco,'k00_vlrcor'))."','$this->k00_vlrcor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrjur"]))
           $resac = db_query("insert into db_acount values($acount,2220,10021,'".AddSlashes(pg_result($resaco,$conresaco,'k00_vlrjur'))."','$this->k00_vlrjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrmul"]))
           $resac = db_query("insert into db_acount values($acount,2220,10022,'".AddSlashes(pg_result($resaco,$conresaco,'k00_vlrmul'))."','$this->k00_vlrmul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrdes"]))
           $resac = db_query("insert into db_acount values($acount,2220,10025,'".AddSlashes(pg_result($resaco,$conresaco,'k00_vlrdes'))."','$this->k00_vlrdes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arresusp nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arresusp nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k00_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k00_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11816,'$k00_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2220,11816,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,12707,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_suspensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,361,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,362,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,372,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,373,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_dtoper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,374,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,375,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,376,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,377,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,378,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,379,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numdig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,380,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,381,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_tipojm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,10020,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,10021,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_vlrjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,10022,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_vlrmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2220,10025,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_vlrdes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from arresusp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k00_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k00_sequencial = $k00_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arresusp nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k00_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arresusp nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k00_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k00_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:arresusp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k00_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from arresusp ";
     $sql .= "      inner join suspensao  on  suspensao.ar18_sequencial = arresusp.k00_suspensao";
     $sql .= "      inner join db_config  on  db_config.codigo = suspensao.ar18_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = suspensao.ar18_usuario";
     $sql .= "      inner join procjur  on  procjur.v62_sequencial = suspensao.ar18_procjur";
     $sql2 = "";
     if($dbwhere==""){
       if($k00_sequencial!=null ){
         $sql2 .= " where arresusp.k00_sequencial = $k00_sequencial "; 
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
   function sql_query_file ( $k00_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from arresusp ";
     $sql2 = "";
     if($dbwhere==""){
       if($k00_sequencial!=null ){
         $sql2 .= " where arresusp.k00_sequencial = $k00_sequencial "; 
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
  
  /**
   * 
   */
   function sql_query_debitosSuspensos($sTipoPesquisa, $sChavePesquisa, $aReceitas, $aTiposDebitos) {

     switch ( $sTipoPesquisa ) {

       case "M":
         $sSqlInnerTabela = " inner join arrematric on arrematric.k00_numpre = arresusp.k00_numpre ";
         $sSqlWhereTabela = " arrematric.k00_matric = {$sChavePesquisa}                            ";
       break;
       case "I":
         $sSqlInnerTabela = " inner join arreinscr on arreinscr.k00_numpre = arresusp.k00_numpre   ";
         $sSqlWhereTabela = " arreinscr.k00_inscr  = {$sChavePesquisa}                             ";     
       break;
       case "C": 
         $sSqlInnerTabela = " inner join arrenumcgm on arrenumcgm.k00_numpre = arresusp.k00_numpre ";
         $sSqlWhereTabela = " arrenumcgm.k00_numcgm = {$sChavePesquisa}                            ";     
       break;
       case "N":
         $sSqlInnerTabela = "";
         $sSqlWhereTabela = " arresusp.k00_numpre   = {$sChavePesquisa}                            ";   
       break;
       default:
         throw new ParameterException("Nenhum parâmetro válido informado");
       break;
     }   
     $sReceitas      = implode(', ', $aReceitas);
     $sDebitos       = implode(', ', $aTiposDebitos);
     $sSqlSuspensao  = " select sum(arresusp.k00_valor )  as valor_historico,                              \n";
     $sSqlSuspensao .= "        sum(arresusp.k00_vlrcor)  as valor_corrigido,                              \n";
     $sSqlSuspensao .= "        sum(arresusp.k00_vlrjur)  as valor_juros,                                  \n";
     $sSqlSuspensao .= "        sum(arresusp.k00_vlrmul)  as valor_multas,                                 \n";
     $sSqlSuspensao .= "        sum(arresusp.k00_vlrdes)  as valor_descontos,                              \n";
     $sSqlSuspensao .= "        sum(arresusp.k00_valor  +                                                  \n";
     $sSqlSuspensao .= "            arresusp.k00_vlrcor +                                                  \n";
     $sSqlSuspensao .= "            arresusp.k00_vlrjur +                                                  \n";
     $sSqlSuspensao .= "            arresusp.k00_vlrmul +                                                  \n";
     $sSqlSuspensao .= "            arresusp.k00_vlrdes ) as valor_total,                                  \n";
     $sSqlSuspensao .= "        arretipo.k00_tipo,                                                         \n";
     $sSqlSuspensao .= "        arretipo.k00_descr                                                         \n";
     $sSqlSuspensao .= "   from arresusp                                                                   \n";
     $sSqlSuspensao .= "        inner join suspensao on suspensao.ar18_sequencial = arresusp.k00_suspensao \n";
     $sSqlSuspensao .= "        inner join arretipo  on arretipo.k00_tipo = arresusp.k00_tipo              \n";
     $sSqlSuspensao .= "        {$sSqlInnerTabela}                                                         \n";
     $sSqlSuspensao .= "   where {$sSqlWhereTabela}                                                        \n";
     $sSqlSuspensao .= "     and suspensao.ar18_situacao = 1                                               \n";
     if (trim($sReceitas) != ""){                                                                          
       $sSqlSuspensao .= "   and arresusp.k00_receit in ({$sReceitas})                                     \n";
     }                                                                                                     
     $sSqlSuspensao .= "     and arresusp.k00_tipo   in ({$sDebitos})                                      \n";
     $sSqlSuspensao .= "     and arretipo.k00_instit = ".db_getsession('DB_instit')."                      \n";
     $sSqlSuspensao .= "   group by arretipo.k00_descr,                                                    \n";
     $sSqlSuspensao .= "            arretipo.k00_tipo                                                      \n";
     return $sSqlSuspensao; 
  }

}
?>