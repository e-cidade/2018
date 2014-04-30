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

//MODULO: pessoal
//CLASSE DA ENTIDADE rescisao
class cl_rescisao { 
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
   var $r59_instit = 0; 
   var $r59_anousu = 0; 
   var $r59_mesusu = 0; 
   var $r59_regime = 0; 
   var $r59_causa = 0; 
   var $r59_descr = null; 
   var $r59_caub = null; 
   var $r59_descr1 = null; 
   var $r59_menos1 = null; 
   var $r59_aviso = 'f'; 
   var $r59_13sal = 'f'; 
   var $r59_fvenc = 'f'; 
   var $r59_fprop = 'f'; 
   var $r59_tercof = 0; 
   var $r59_codsaq = null; 
   var $r59_mfgts = 0; 
   var $r59_479clt = 'f'; 
   var $r59_grfp = 'f'; 
   var $r59_finss = 'f'; 
   var $r59_ffgts = 'f'; 
   var $r59_firrf = 'f'; 
   var $r59_13inss = 'f'; 
   var $r59_13fgts = 'f'; 
   var $r59_13irrf = 'f'; 
   var $r59_rinss = 'f'; 
   var $r59_rfgts = 'f'; 
   var $r59_rirrf = 'f'; 
   var $r59_movsef = null; 
   var $r59_causaafastamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r59_instit = int4 = Cod. Instituição 
                 r59_anousu = int4 = Ano do Exercicio 
                 r59_mesusu = int4 = Mes do Exercicio 
                 r59_regime = int4 = Código do Regime do Servidor 
                 r59_causa = int4 = Causa da Rescisão 
                 r59_descr = char(    40) = Descricao da causa rescisao 
                 r59_caub = char(2) = Subdivisão da Causa de Rescisão 
                 r59_descr1 = char(40) = Descricao da divisao da causa 
                 r59_menos1 = char(1) = Servidor com Menos de 1 Ano 
                 r59_aviso = bool = Aviso Prévio 
                 r59_13sal = bool = Paga 13º Proporcional na Rescisão 
                 r59_fvenc = bool = Paga Férias Vencidas na Rescisão 
                 r59_fprop = bool = Paga Férias Proporcionais na Rescisão 
                 r59_tercof = float8 = Paga 1/3 de Férias / Percentual 
                 r59_codsaq = char(2) = Código para Saque do FGTS 
                 r59_mfgts = float8 = Percentual de Multa do FGTS 
                 r59_479clt = bool = Pagar art. 479 da CLT 
                 r59_grfp = bool = Gerar GRFC para Pagamento 
                 r59_finss = boolean = desconta previdencia nas feri? 
                 r59_ffgts = boolean = incide fgts s/ferias? 
                 r59_firrf = boolean = incide IR s/ferias? 
                 r59_13inss = boolean = incide previdencia s/13.sal? 
                 r59_13fgts = boolean = incide fgts s/13.sal? 
                 r59_13irrf = boolean = incide IR s/13.sal? 
                 r59_rinss = boolean = incide previd.s/aviso indeniz? 
                 r59_rfgts = boolean = incide fgts s/aviso indeniz.? 
                 r59_rirrf = boolean = incide IR s/aviso indeniz.? 
                 r59_movsef = varchar(2) = Código de Movimentação da SEFIP 
                 r59_causaafastamento = int4 = Causas de afastamento 
                 ";
   //funcao construtor da classe 
   function cl_rescisao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rescisao"); 
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
       $this->r59_instit = ($this->r59_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_instit"]:$this->r59_instit);
       $this->r59_anousu = ($this->r59_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_anousu"]:$this->r59_anousu);
       $this->r59_mesusu = ($this->r59_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_mesusu"]:$this->r59_mesusu);
       $this->r59_regime = ($this->r59_regime == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_regime"]:$this->r59_regime);
       $this->r59_causa = ($this->r59_causa == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_causa"]:$this->r59_causa);
       $this->r59_descr = ($this->r59_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_descr"]:$this->r59_descr);
       $this->r59_caub = ($this->r59_caub == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_caub"]:$this->r59_caub);
       $this->r59_descr1 = ($this->r59_descr1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_descr1"]:$this->r59_descr1);
       $this->r59_menos1 = ($this->r59_menos1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_menos1"]:$this->r59_menos1);
       $this->r59_aviso = ($this->r59_aviso == "f"?@$GLOBALS["HTTP_POST_VARS"]["r59_aviso"]:$this->r59_aviso);
       $this->r59_13sal = ($this->r59_13sal == "f"?@$GLOBALS["HTTP_POST_VARS"]["r59_13sal"]:$this->r59_13sal);
       $this->r59_fvenc = ($this->r59_fvenc == "f"?@$GLOBALS["HTTP_POST_VARS"]["r59_fvenc"]:$this->r59_fvenc);
       $this->r59_fprop = ($this->r59_fprop == "f"?@$GLOBALS["HTTP_POST_VARS"]["r59_fprop"]:$this->r59_fprop);
       $this->r59_tercof = ($this->r59_tercof == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_tercof"]:$this->r59_tercof);
       $this->r59_codsaq = ($this->r59_codsaq == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_codsaq"]:$this->r59_codsaq);
       $this->r59_mfgts = ($this->r59_mfgts == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_mfgts"]:$this->r59_mfgts);
       $this->r59_479clt = ($this->r59_479clt == "f"?@$GLOBALS["HTTP_POST_VARS"]["r59_479clt"]:$this->r59_479clt);
       $this->r59_grfp = ($this->r59_grfp == "f"?@$GLOBALS["HTTP_POST_VARS"]["r59_grfp"]:$this->r59_grfp);
       $this->r59_finss = ($this->r59_finss == "f"?@$GLOBALS["HTTP_POST_VARS"]["r59_finss"]:$this->r59_finss);
       $this->r59_ffgts = ($this->r59_ffgts == "f"?@$GLOBALS["HTTP_POST_VARS"]["r59_ffgts"]:$this->r59_ffgts);
       $this->r59_firrf = ($this->r59_firrf == "f"?@$GLOBALS["HTTP_POST_VARS"]["r59_firrf"]:$this->r59_firrf);
       $this->r59_13inss = ($this->r59_13inss == "f"?@$GLOBALS["HTTP_POST_VARS"]["r59_13inss"]:$this->r59_13inss);
       $this->r59_13fgts = ($this->r59_13fgts == "f"?@$GLOBALS["HTTP_POST_VARS"]["r59_13fgts"]:$this->r59_13fgts);
       $this->r59_13irrf = ($this->r59_13irrf == "f"?@$GLOBALS["HTTP_POST_VARS"]["r59_13irrf"]:$this->r59_13irrf);
       $this->r59_rinss = ($this->r59_rinss == "f"?@$GLOBALS["HTTP_POST_VARS"]["r59_rinss"]:$this->r59_rinss);
       $this->r59_rfgts = ($this->r59_rfgts == "f"?@$GLOBALS["HTTP_POST_VARS"]["r59_rfgts"]:$this->r59_rfgts);
       $this->r59_rirrf = ($this->r59_rirrf == "f"?@$GLOBALS["HTTP_POST_VARS"]["r59_rirrf"]:$this->r59_rirrf);
       $this->r59_movsef = ($this->r59_movsef == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_movsef"]:$this->r59_movsef);
       $this->r59_causaafastamento = ($this->r59_causaafastamento == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_causaafastamento"]:$this->r59_causaafastamento);
     }else{
       $this->r59_instit = ($this->r59_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_instit"]:$this->r59_instit);
       $this->r59_anousu = ($this->r59_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_anousu"]:$this->r59_anousu);
       $this->r59_mesusu = ($this->r59_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_mesusu"]:$this->r59_mesusu);
       $this->r59_regime = ($this->r59_regime == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_regime"]:$this->r59_regime);
       $this->r59_causa = ($this->r59_causa == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_causa"]:$this->r59_causa);
       $this->r59_caub = ($this->r59_caub == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_caub"]:$this->r59_caub);
       $this->r59_menos1 = ($this->r59_menos1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r59_menos1"]:$this->r59_menos1);
     }
   }
   // funcao para inclusao
   function incluir ($r59_anousu,$r59_mesusu,$r59_regime,$r59_causa,$r59_caub,$r59_menos1,$r59_instit){ 
      $this->atualizacampos();
     if($this->r59_descr == null ){ 
       $this->erro_sql = " Campo Descricao da causa rescisao nao Informado.";
       $this->erro_campo = "r59_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_aviso == null ){ 
       $this->erro_sql = " Campo Aviso Prévio nao Informado.";
       $this->erro_campo = "r59_aviso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_13sal == null ){ 
       $this->erro_sql = " Campo Paga 13º Proporcional na Rescisão nao Informado.";
       $this->erro_campo = "r59_13sal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_fvenc == null ){ 
       $this->erro_sql = " Campo Paga Férias Vencidas na Rescisão nao Informado.";
       $this->erro_campo = "r59_fvenc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_fprop == null ){ 
       $this->erro_sql = " Campo Paga Férias Proporcionais na Rescisão nao Informado.";
       $this->erro_campo = "r59_fprop";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_tercof == null ){ 
       $this->erro_sql = " Campo Paga 1/3 de Férias / Percentual nao Informado.";
       $this->erro_campo = "r59_tercof";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_mfgts == null ){ 
       $this->erro_sql = " Campo Percentual de Multa do FGTS nao Informado.";
       $this->erro_campo = "r59_mfgts";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_479clt == null ){ 
       $this->erro_sql = " Campo Pagar art. 479 da CLT nao Informado.";
       $this->erro_campo = "r59_479clt";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_grfp == null ){ 
       $this->erro_sql = " Campo Gerar GRFC para Pagamento nao Informado.";
       $this->erro_campo = "r59_grfp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_finss == null ){ 
       $this->erro_sql = " Campo desconta previdencia nas feri? nao Informado.";
       $this->erro_campo = "r59_finss";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_ffgts == null ){ 
       $this->erro_sql = " Campo incide fgts s/ferias? nao Informado.";
       $this->erro_campo = "r59_ffgts";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_firrf == null ){ 
       $this->erro_sql = " Campo incide IR s/ferias? nao Informado.";
       $this->erro_campo = "r59_firrf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_13inss == null ){ 
       $this->erro_sql = " Campo incide previdencia s/13.sal? nao Informado.";
       $this->erro_campo = "r59_13inss";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_13fgts == null ){ 
       $this->erro_sql = " Campo incide fgts s/13.sal? nao Informado.";
       $this->erro_campo = "r59_13fgts";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_13irrf == null ){ 
       $this->erro_sql = " Campo incide IR s/13.sal? nao Informado.";
       $this->erro_campo = "r59_13irrf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_rinss == null ){ 
       $this->erro_sql = " Campo incide previd.s/aviso indeniz? nao Informado.";
       $this->erro_campo = "r59_rinss";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_rfgts == null ){ 
       $this->erro_sql = " Campo incide fgts s/aviso indeniz.? nao Informado.";
       $this->erro_campo = "r59_rfgts";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_rirrf == null ){ 
       $this->erro_sql = " Campo incide IR s/aviso indeniz.? nao Informado.";
       $this->erro_campo = "r59_rirrf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_movsef == null ){ 
       $this->erro_sql = " Campo Código de Movimentação da SEFIP nao Informado.";
       $this->erro_campo = "r59_movsef";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r59_causaafastamento == null ){ 
       $this->erro_sql = " Campo Causas de afastamento nao Informado.";
       $this->erro_campo = "r59_causaafastamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r59_anousu = $r59_anousu; 
       $this->r59_mesusu = $r59_mesusu; 
       $this->r59_regime = $r59_regime; 
       $this->r59_causa = $r59_causa; 
       $this->r59_caub = $r59_caub; 
       $this->r59_menos1 = $r59_menos1; 
       $this->r59_instit = $r59_instit; 
     if(($this->r59_anousu == null) || ($this->r59_anousu == "") ){ 
       $this->erro_sql = " Campo r59_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r59_mesusu == null) || ($this->r59_mesusu == "") ){ 
       $this->erro_sql = " Campo r59_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r59_regime == null) || ($this->r59_regime == "") ){ 
       $this->erro_sql = " Campo r59_regime nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r59_causa == null) || ($this->r59_causa == "") ){ 
       $this->erro_sql = " Campo r59_causa nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r59_caub == null) || ($this->r59_caub == "") ){ 
       $this->erro_sql = " Campo r59_caub nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r59_menos1 == null) || ($this->r59_menos1 == "") ){ 
       $this->erro_sql = " Campo r59_menos1 nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r59_instit == null) || ($this->r59_instit == "") ){ 
       $this->erro_sql = " Campo r59_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rescisao(
                                       r59_instit 
                                      ,r59_anousu 
                                      ,r59_mesusu 
                                      ,r59_regime 
                                      ,r59_causa 
                                      ,r59_descr 
                                      ,r59_caub 
                                      ,r59_descr1 
                                      ,r59_menos1 
                                      ,r59_aviso 
                                      ,r59_13sal 
                                      ,r59_fvenc 
                                      ,r59_fprop 
                                      ,r59_tercof 
                                      ,r59_codsaq 
                                      ,r59_mfgts 
                                      ,r59_479clt 
                                      ,r59_grfp 
                                      ,r59_finss 
                                      ,r59_ffgts 
                                      ,r59_firrf 
                                      ,r59_13inss 
                                      ,r59_13fgts 
                                      ,r59_13irrf 
                                      ,r59_rinss 
                                      ,r59_rfgts 
                                      ,r59_rirrf 
                                      ,r59_movsef 
                                      ,r59_causaafastamento 
                       )
                values (
                                $this->r59_instit 
                               ,$this->r59_anousu 
                               ,$this->r59_mesusu 
                               ,$this->r59_regime 
                               ,$this->r59_causa 
                               ,'$this->r59_descr' 
                               ,'$this->r59_caub' 
                               ,'$this->r59_descr1' 
                               ,'$this->r59_menos1' 
                               ,'$this->r59_aviso' 
                               ,'$this->r59_13sal' 
                               ,'$this->r59_fvenc' 
                               ,'$this->r59_fprop' 
                               ,$this->r59_tercof 
                               ,'$this->r59_codsaq' 
                               ,$this->r59_mfgts 
                               ,'$this->r59_479clt' 
                               ,'$this->r59_grfp' 
                               ,'$this->r59_finss' 
                               ,'$this->r59_ffgts' 
                               ,'$this->r59_firrf' 
                               ,'$this->r59_13inss' 
                               ,'$this->r59_13fgts' 
                               ,'$this->r59_13irrf' 
                               ,'$this->r59_rinss' 
                               ,'$this->r59_rfgts' 
                               ,'$this->r59_rirrf' 
                               ,'$this->r59_movsef' 
                               ,$this->r59_causaafastamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros de Rescisao ($this->r59_anousu."-".$this->r59_mesusu."-".$this->r59_regime."-".$this->r59_causa."-".$this->r59_caub."-".$this->r59_menos1."-".$this->r59_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros de Rescisao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros de Rescisao ($this->r59_anousu."-".$this->r59_mesusu."-".$this->r59_regime."-".$this->r59_causa."-".$this->r59_caub."-".$this->r59_menos1."-".$this->r59_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r59_anousu."-".$this->r59_mesusu."-".$this->r59_regime."-".$this->r59_causa."-".$this->r59_caub."-".$this->r59_menos1."-".$this->r59_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r59_anousu,$this->r59_mesusu,$this->r59_regime,$this->r59_causa,$this->r59_caub,$this->r59_menos1,$this->r59_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4417,'$this->r59_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4418,'$this->r59_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4419,'$this->r59_regime','I')");
       $resac = db_query("insert into db_acountkey values($acount,4420,'$this->r59_causa','I')");
       $resac = db_query("insert into db_acountkey values($acount,4422,'$this->r59_caub','I')");
       $resac = db_query("insert into db_acountkey values($acount,4424,'$this->r59_menos1','I')");
       $resac = db_query("insert into db_acountkey values($acount,9900,'$this->r59_instit','I')");
       $resac = db_query("insert into db_acount values($acount,589,9900,'','".AddSlashes(pg_result($resaco,0,'r59_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4417,'','".AddSlashes(pg_result($resaco,0,'r59_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4418,'','".AddSlashes(pg_result($resaco,0,'r59_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4419,'','".AddSlashes(pg_result($resaco,0,'r59_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4420,'','".AddSlashes(pg_result($resaco,0,'r59_causa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4421,'','".AddSlashes(pg_result($resaco,0,'r59_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4422,'','".AddSlashes(pg_result($resaco,0,'r59_caub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4423,'','".AddSlashes(pg_result($resaco,0,'r59_descr1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4424,'','".AddSlashes(pg_result($resaco,0,'r59_menos1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4425,'','".AddSlashes(pg_result($resaco,0,'r59_aviso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4426,'','".AddSlashes(pg_result($resaco,0,'r59_13sal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4427,'','".AddSlashes(pg_result($resaco,0,'r59_fvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4428,'','".AddSlashes(pg_result($resaco,0,'r59_fprop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4429,'','".AddSlashes(pg_result($resaco,0,'r59_tercof'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4430,'','".AddSlashes(pg_result($resaco,0,'r59_codsaq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4431,'','".AddSlashes(pg_result($resaco,0,'r59_mfgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4432,'','".AddSlashes(pg_result($resaco,0,'r59_479clt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4433,'','".AddSlashes(pg_result($resaco,0,'r59_grfp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4434,'','".AddSlashes(pg_result($resaco,0,'r59_finss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4435,'','".AddSlashes(pg_result($resaco,0,'r59_ffgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4436,'','".AddSlashes(pg_result($resaco,0,'r59_firrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4437,'','".AddSlashes(pg_result($resaco,0,'r59_13inss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4438,'','".AddSlashes(pg_result($resaco,0,'r59_13fgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4439,'','".AddSlashes(pg_result($resaco,0,'r59_13irrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4440,'','".AddSlashes(pg_result($resaco,0,'r59_rinss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4441,'','".AddSlashes(pg_result($resaco,0,'r59_rfgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4442,'','".AddSlashes(pg_result($resaco,0,'r59_rirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,4610,'','".AddSlashes(pg_result($resaco,0,'r59_movsef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,589,19580,'','".AddSlashes(pg_result($resaco,0,'r59_causaafastamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r59_anousu=null,$r59_mesusu=null,$r59_regime=null,$r59_causa=null,$r59_caub=null,$r59_menos1=null,$r59_instit=null) { 
      $this->atualizacampos();
     $sql = " update rescisao set ";
     $virgula = "";
     if(trim($this->r59_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_instit"])){ 
       $sql  .= $virgula." r59_instit = $this->r59_instit ";
       $virgula = ",";
       if(trim($this->r59_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "r59_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_anousu"])){ 
       $sql  .= $virgula." r59_anousu = $this->r59_anousu ";
       $virgula = ",";
       if(trim($this->r59_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r59_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_mesusu"])){ 
       $sql  .= $virgula." r59_mesusu = $this->r59_mesusu ";
       $virgula = ",";
       if(trim($this->r59_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r59_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_regime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_regime"])){ 
       $sql  .= $virgula." r59_regime = $this->r59_regime ";
       $virgula = ",";
       if(trim($this->r59_regime) == null ){ 
         $this->erro_sql = " Campo Código do Regime do Servidor nao Informado.";
         $this->erro_campo = "r59_regime";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_causa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_causa"])){ 
       $sql  .= $virgula." r59_causa = $this->r59_causa ";
       $virgula = ",";
       if(trim($this->r59_causa) == null ){ 
         $this->erro_sql = " Campo Causa da Rescisão nao Informado.";
         $this->erro_campo = "r59_causa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_descr"])){ 
       $sql  .= $virgula." r59_descr = '$this->r59_descr' ";
       $virgula = ",";
       if(trim($this->r59_descr) == null ){ 
         $this->erro_sql = " Campo Descricao da causa rescisao nao Informado.";
         $this->erro_campo = "r59_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_caub)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_caub"])){ 
       $sql  .= $virgula." r59_caub = '$this->r59_caub' ";
       $virgula = ",";
     }
     if(trim($this->r59_descr1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_descr1"])){ 
       $sql  .= $virgula." r59_descr1 = '$this->r59_descr1' ";
       $virgula = ",";
     }
     if(trim($this->r59_menos1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_menos1"])){ 
       $sql  .= $virgula." r59_menos1 = '$this->r59_menos1' ";
       $virgula = ",";
       if(trim($this->r59_menos1) == null ){ 
         $this->erro_sql = " Campo Servidor com Menos de 1 Ano nao Informado.";
         $this->erro_campo = "r59_menos1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_aviso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_aviso"])){ 
       $sql  .= $virgula." r59_aviso = '$this->r59_aviso' ";
       $virgula = ",";
       if(trim($this->r59_aviso) == null ){ 
         $this->erro_sql = " Campo Aviso Prévio nao Informado.";
         $this->erro_campo = "r59_aviso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_13sal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_13sal"])){ 
       $sql  .= $virgula." r59_13sal = '$this->r59_13sal' ";
       $virgula = ",";
       if(trim($this->r59_13sal) == null ){ 
         $this->erro_sql = " Campo Paga 13º Proporcional na Rescisão nao Informado.";
         $this->erro_campo = "r59_13sal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_fvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_fvenc"])){ 
       $sql  .= $virgula." r59_fvenc = '$this->r59_fvenc' ";
       $virgula = ",";
       if(trim($this->r59_fvenc) == null ){ 
         $this->erro_sql = " Campo Paga Férias Vencidas na Rescisão nao Informado.";
         $this->erro_campo = "r59_fvenc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_fprop)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_fprop"])){ 
       $sql  .= $virgula." r59_fprop = '$this->r59_fprop' ";
       $virgula = ",";
       if(trim($this->r59_fprop) == null ){ 
         $this->erro_sql = " Campo Paga Férias Proporcionais na Rescisão nao Informado.";
         $this->erro_campo = "r59_fprop";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_tercof)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_tercof"])){ 
       $sql  .= $virgula." r59_tercof = $this->r59_tercof ";
       $virgula = ",";
       if(trim($this->r59_tercof) == null ){ 
         $this->erro_sql = " Campo Paga 1/3 de Férias / Percentual nao Informado.";
         $this->erro_campo = "r59_tercof";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_codsaq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_codsaq"])){ 
       $sql  .= $virgula." r59_codsaq = '$this->r59_codsaq' ";
       $virgula = ",";
     }
     if(trim($this->r59_mfgts)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_mfgts"])){ 
       $sql  .= $virgula." r59_mfgts = $this->r59_mfgts ";
       $virgula = ",";
       if(trim($this->r59_mfgts) == null ){ 
         $this->erro_sql = " Campo Percentual de Multa do FGTS nao Informado.";
         $this->erro_campo = "r59_mfgts";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_479clt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_479clt"])){ 
       $sql  .= $virgula." r59_479clt = '$this->r59_479clt' ";
       $virgula = ",";
       if(trim($this->r59_479clt) == null ){ 
         $this->erro_sql = " Campo Pagar art. 479 da CLT nao Informado.";
         $this->erro_campo = "r59_479clt";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_grfp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_grfp"])){ 
       $sql  .= $virgula." r59_grfp = '$this->r59_grfp' ";
       $virgula = ",";
       if(trim($this->r59_grfp) == null ){ 
         $this->erro_sql = " Campo Gerar GRFC para Pagamento nao Informado.";
         $this->erro_campo = "r59_grfp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_finss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_finss"])){ 
       $sql  .= $virgula." r59_finss = '$this->r59_finss' ";
       $virgula = ",";
       if(trim($this->r59_finss) == null ){ 
         $this->erro_sql = " Campo desconta previdencia nas feri? nao Informado.";
         $this->erro_campo = "r59_finss";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_ffgts)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_ffgts"])){ 
       $sql  .= $virgula." r59_ffgts = '$this->r59_ffgts' ";
       $virgula = ",";
       if(trim($this->r59_ffgts) == null ){ 
         $this->erro_sql = " Campo incide fgts s/ferias? nao Informado.";
         $this->erro_campo = "r59_ffgts";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_firrf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_firrf"])){ 
       $sql  .= $virgula." r59_firrf = '$this->r59_firrf' ";
       $virgula = ",";
       if(trim($this->r59_firrf) == null ){ 
         $this->erro_sql = " Campo incide IR s/ferias? nao Informado.";
         $this->erro_campo = "r59_firrf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_13inss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_13inss"])){ 
       $sql  .= $virgula." r59_13inss = '$this->r59_13inss' ";
       $virgula = ",";
       if(trim($this->r59_13inss) == null ){ 
         $this->erro_sql = " Campo incide previdencia s/13.sal? nao Informado.";
         $this->erro_campo = "r59_13inss";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_13fgts)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_13fgts"])){ 
       $sql  .= $virgula." r59_13fgts = '$this->r59_13fgts' ";
       $virgula = ",";
       if(trim($this->r59_13fgts) == null ){ 
         $this->erro_sql = " Campo incide fgts s/13.sal? nao Informado.";
         $this->erro_campo = "r59_13fgts";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_13irrf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_13irrf"])){ 
       $sql  .= $virgula." r59_13irrf = '$this->r59_13irrf' ";
       $virgula = ",";
       if(trim($this->r59_13irrf) == null ){ 
         $this->erro_sql = " Campo incide IR s/13.sal? nao Informado.";
         $this->erro_campo = "r59_13irrf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_rinss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_rinss"])){ 
       $sql  .= $virgula." r59_rinss = '$this->r59_rinss' ";
       $virgula = ",";
       if(trim($this->r59_rinss) == null ){ 
         $this->erro_sql = " Campo incide previd.s/aviso indeniz? nao Informado.";
         $this->erro_campo = "r59_rinss";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_rfgts)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_rfgts"])){ 
       $sql  .= $virgula." r59_rfgts = '$this->r59_rfgts' ";
       $virgula = ",";
       if(trim($this->r59_rfgts) == null ){ 
         $this->erro_sql = " Campo incide fgts s/aviso indeniz.? nao Informado.";
         $this->erro_campo = "r59_rfgts";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_rirrf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_rirrf"])){ 
       $sql  .= $virgula." r59_rirrf = '$this->r59_rirrf' ";
       $virgula = ",";
       if(trim($this->r59_rirrf) == null ){ 
         $this->erro_sql = " Campo incide IR s/aviso indeniz.? nao Informado.";
         $this->erro_campo = "r59_rirrf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_movsef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_movsef"])){ 
       $sql  .= $virgula." r59_movsef = '$this->r59_movsef' ";
       $virgula = ",";
       if(trim($this->r59_movsef) == null ){ 
         $this->erro_sql = " Campo Código de Movimentação da SEFIP nao Informado.";
         $this->erro_campo = "r59_movsef";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r59_causaafastamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r59_causaafastamento"])){ 
       $sql  .= $virgula." r59_causaafastamento = $this->r59_causaafastamento ";
       $virgula = ",";
       if(trim($this->r59_causaafastamento) == null ){ 
         $this->erro_sql = " Campo Causas de afastamento nao Informado.";
         $this->erro_campo = "r59_causaafastamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r59_anousu!=null){
       $sql .= " r59_anousu = $this->r59_anousu";
     }
     if($r59_mesusu!=null){
       $sql .= " and  r59_mesusu = $this->r59_mesusu";
     }
     if($r59_regime!=null){
       $sql .= " and  r59_regime = $this->r59_regime";
     }
     if($r59_causa!=null){
       $sql .= " and  r59_causa = $this->r59_causa";
     }
     if($r59_caub!=null || $r59_caub == ""){
       $sql .= " and  r59_caub = '$this->r59_caub'";
     }
     if($r59_menos1!=null){
       $sql .= " and  r59_menos1 = '$this->r59_menos1'";
     }
     if($r59_instit!=null){
       $sql .= " and  r59_instit = $this->r59_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r59_anousu,$this->r59_mesusu,$this->r59_regime,$this->r59_causa,$this->r59_caub,$this->r59_menos1,$this->r59_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4417,'$this->r59_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4418,'$this->r59_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4419,'$this->r59_regime','A')");
         $resac = db_query("insert into db_acountkey values($acount,4420,'$this->r59_causa','A')");
         $resac = db_query("insert into db_acountkey values($acount,4422,'$this->r59_caub','A')");
         $resac = db_query("insert into db_acountkey values($acount,4424,'$this->r59_menos1','A')");
         $resac = db_query("insert into db_acountkey values($acount,9900,'$this->r59_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_instit"]))
           $resac = db_query("insert into db_acount values($acount,589,9900,'".AddSlashes(pg_result($resaco,$conresaco,'r59_instit'))."','$this->r59_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_anousu"]))
           $resac = db_query("insert into db_acount values($acount,589,4417,'".AddSlashes(pg_result($resaco,$conresaco,'r59_anousu'))."','$this->r59_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,589,4418,'".AddSlashes(pg_result($resaco,$conresaco,'r59_mesusu'))."','$this->r59_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_regime"]))
           $resac = db_query("insert into db_acount values($acount,589,4419,'".AddSlashes(pg_result($resaco,$conresaco,'r59_regime'))."','$this->r59_regime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_causa"]))
           $resac = db_query("insert into db_acount values($acount,589,4420,'".AddSlashes(pg_result($resaco,$conresaco,'r59_causa'))."','$this->r59_causa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_descr"]))
           $resac = db_query("insert into db_acount values($acount,589,4421,'".AddSlashes(pg_result($resaco,$conresaco,'r59_descr'))."','$this->r59_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_caub"]))
           $resac = db_query("insert into db_acount values($acount,589,4422,'".AddSlashes(pg_result($resaco,$conresaco,'r59_caub'))."','$this->r59_caub',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_descr1"]))
           $resac = db_query("insert into db_acount values($acount,589,4423,'".AddSlashes(pg_result($resaco,$conresaco,'r59_descr1'))."','$this->r59_descr1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_menos1"]))
           $resac = db_query("insert into db_acount values($acount,589,4424,'".AddSlashes(pg_result($resaco,$conresaco,'r59_menos1'))."','$this->r59_menos1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_aviso"]))
           $resac = db_query("insert into db_acount values($acount,589,4425,'".AddSlashes(pg_result($resaco,$conresaco,'r59_aviso'))."','$this->r59_aviso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_13sal"]))
           $resac = db_query("insert into db_acount values($acount,589,4426,'".AddSlashes(pg_result($resaco,$conresaco,'r59_13sal'))."','$this->r59_13sal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_fvenc"]))
           $resac = db_query("insert into db_acount values($acount,589,4427,'".AddSlashes(pg_result($resaco,$conresaco,'r59_fvenc'))."','$this->r59_fvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_fprop"]))
           $resac = db_query("insert into db_acount values($acount,589,4428,'".AddSlashes(pg_result($resaco,$conresaco,'r59_fprop'))."','$this->r59_fprop',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_tercof"]))
           $resac = db_query("insert into db_acount values($acount,589,4429,'".AddSlashes(pg_result($resaco,$conresaco,'r59_tercof'))."','$this->r59_tercof',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_codsaq"]))
           $resac = db_query("insert into db_acount values($acount,589,4430,'".AddSlashes(pg_result($resaco,$conresaco,'r59_codsaq'))."','$this->r59_codsaq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_mfgts"]))
           $resac = db_query("insert into db_acount values($acount,589,4431,'".AddSlashes(pg_result($resaco,$conresaco,'r59_mfgts'))."','$this->r59_mfgts',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_479clt"]))
           $resac = db_query("insert into db_acount values($acount,589,4432,'".AddSlashes(pg_result($resaco,$conresaco,'r59_479clt'))."','$this->r59_479clt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_grfp"]))
           $resac = db_query("insert into db_acount values($acount,589,4433,'".AddSlashes(pg_result($resaco,$conresaco,'r59_grfp'))."','$this->r59_grfp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_finss"]))
           $resac = db_query("insert into db_acount values($acount,589,4434,'".AddSlashes(pg_result($resaco,$conresaco,'r59_finss'))."','$this->r59_finss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_ffgts"]))
           $resac = db_query("insert into db_acount values($acount,589,4435,'".AddSlashes(pg_result($resaco,$conresaco,'r59_ffgts'))."','$this->r59_ffgts',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_firrf"]))
           $resac = db_query("insert into db_acount values($acount,589,4436,'".AddSlashes(pg_result($resaco,$conresaco,'r59_firrf'))."','$this->r59_firrf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_13inss"]))
           $resac = db_query("insert into db_acount values($acount,589,4437,'".AddSlashes(pg_result($resaco,$conresaco,'r59_13inss'))."','$this->r59_13inss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_13fgts"]))
           $resac = db_query("insert into db_acount values($acount,589,4438,'".AddSlashes(pg_result($resaco,$conresaco,'r59_13fgts'))."','$this->r59_13fgts',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_13irrf"]))
           $resac = db_query("insert into db_acount values($acount,589,4439,'".AddSlashes(pg_result($resaco,$conresaco,'r59_13irrf'))."','$this->r59_13irrf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_rinss"]))
           $resac = db_query("insert into db_acount values($acount,589,4440,'".AddSlashes(pg_result($resaco,$conresaco,'r59_rinss'))."','$this->r59_rinss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_rfgts"]))
           $resac = db_query("insert into db_acount values($acount,589,4441,'".AddSlashes(pg_result($resaco,$conresaco,'r59_rfgts'))."','$this->r59_rfgts',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_rirrf"]))
           $resac = db_query("insert into db_acount values($acount,589,4442,'".AddSlashes(pg_result($resaco,$conresaco,'r59_rirrf'))."','$this->r59_rirrf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_movsef"]))
           $resac = db_query("insert into db_acount values($acount,589,4610,'".AddSlashes(pg_result($resaco,$conresaco,'r59_movsef'))."','$this->r59_movsef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r59_causaafastamento"]) || $this->r59_causaafastamento != "")
           $resac = db_query("insert into db_acount values($acount,589,19580,'".AddSlashes(pg_result($resaco,$conresaco,'r59_causaafastamento'))."','$this->r59_causaafastamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros de Rescisao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r59_anousu."-".$this->r59_mesusu."-".$this->r59_regime."-".$this->r59_causa."-".$this->r59_caub."-".$this->r59_menos1."-".$this->r59_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros de Rescisao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r59_anousu."-".$this->r59_mesusu."-".$this->r59_regime."-".$this->r59_causa."-".$this->r59_caub."-".$this->r59_menos1."-".$this->r59_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r59_anousu."-".$this->r59_mesusu."-".$this->r59_regime."-".$this->r59_causa."-".$this->r59_caub."-".$this->r59_menos1."-".$this->r59_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r59_anousu=null,$r59_mesusu=null,$r59_regime=null,$r59_causa=null,$r59_caub=null,$r59_menos1=null,$r59_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r59_anousu,$r59_mesusu,$r59_regime,$r59_causa,$r59_caub,$r59_menos1,$r59_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4417,'$r59_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4418,'$r59_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4419,'$r59_regime','E')");
         $resac = db_query("insert into db_acountkey values($acount,4420,'$r59_causa','E')");
         $resac = db_query("insert into db_acountkey values($acount,4422,'$r59_caub','E')");
         $resac = db_query("insert into db_acountkey values($acount,4424,'$r59_menos1','E')");
         $resac = db_query("insert into db_acountkey values($acount,9900,'$r59_instit','E')");
         $resac = db_query("insert into db_acount values($acount,589,9900,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4417,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4418,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4419,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4420,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_causa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4421,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4422,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_caub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4423,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_descr1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4424,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_menos1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4425,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_aviso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4426,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_13sal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4427,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_fvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4428,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_fprop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4429,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_tercof'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4430,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_codsaq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4431,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_mfgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4432,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_479clt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4433,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_grfp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4434,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_finss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4435,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_ffgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4436,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_firrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4437,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_13inss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4438,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_13fgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4439,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_13irrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4440,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_rinss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4441,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_rfgts'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4442,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_rirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,4610,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_movsef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,589,19580,'','".AddSlashes(pg_result($resaco,$iresaco,'r59_causaafastamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rescisao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r59_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r59_anousu = $r59_anousu ";
        }
        if($r59_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r59_mesusu = $r59_mesusu ";
        }
        if($r59_regime != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r59_regime = $r59_regime ";
        }
        if($r59_causa != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r59_causa = $r59_causa ";
        }
        if($r59_caub != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r59_caub = '$r59_caub' ";
        }
        if($r59_menos1 != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r59_menos1 = '$r59_menos1' ";
        }
        if($r59_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r59_instit = $r59_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros de Rescisao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r59_anousu."-".$r59_mesusu."-".$r59_regime."-".$r59_causa."-".$r59_caub."-".$r59_menos1."-".$r59_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros de Rescisao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r59_anousu."-".$r59_mesusu."-".$r59_regime."-".$r59_causa."-".$r59_caub."-".$r59_menos1."-".$r59_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r59_anousu."-".$r59_mesusu."-".$r59_regime."-".$r59_causa."-".$r59_caub."-".$r59_menos1."-".$r59_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:rescisao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $r59_anousu=null,$r59_mesusu=null,$r59_regime=null,$r59_causa=null,$r59_caub=null,$r59_menos1=null,$r59_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rescisao ";
     $sql .= "      inner join db_config  on  db_config.codigo = rescisao.r59_instit";
     $sql .= "      inner join causaafastamento  on  causaafastamento.rh115_sequencial = rescisao.r59_causaafastamento";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($r59_anousu!=null ){
         $sql2 .= " where rescisao.r59_anousu = $r59_anousu "; 
       } 
       if($r59_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rescisao.r59_mesusu = $r59_mesusu "; 
       } 
       if($r59_regime!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rescisao.r59_regime = $r59_regime "; 
       } 
       if($r59_causa!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rescisao.r59_causa = $r59_causa "; 
       } 
       if($r59_caub!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rescisao.r59_caub = '$r59_caub' "; 
       } 
       if($r59_menos1!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rescisao.r59_menos1 = '$r59_menos1' "; 
       } 
       if($r59_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rescisao.r59_instit = $r59_instit "; 
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
   function sql_query_file ( $r59_anousu=null,$r59_mesusu=null,$r59_regime=null,$r59_causa=null,$r59_caub=null,$r59_menos1=null,$r59_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rescisao ";
     $sql2 = "";
     if($dbwhere==""){
       if($r59_anousu!=null ){
         $sql2 .= " where rescisao.r59_anousu = $r59_anousu "; 
       } 
       if($r59_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rescisao.r59_mesusu = $r59_mesusu "; 
       } 
       if($r59_regime!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rescisao.r59_regime = $r59_regime "; 
       } 
       if($r59_causa!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rescisao.r59_causa = $r59_causa "; 
       } 
       if($r59_caub!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rescisao.r59_caub = '$r59_caub' "; 
       } 
       if($r59_menos1!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rescisao.r59_menos1 = '$r59_menos1' "; 
       } 
       if($r59_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rescisao.r59_instit = $r59_instit "; 
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

  function atualiza_incluir (){
    $this->incluir($this->r59_anousu,$this->r59_mesusu,$this->r59_regime,$this->r59_causa,$this->r59_caub,$this->r59_menos1,$this->r59_instit);
  }

  function sql_query_termoRescisao($iInstituicao, $sWhere) {

    $sSql  = " SELECT DISTINCT CASE                                                                                     ";
    $sSql .= "                   WHEN Substr(r70_estrut, 10, 2) = '03' THEN 'FUNDEB 60%'                                 ";
    $sSql .= "                   ELSE                                                                                    ";
    $sSql .= "                     CASE                                                                                  ";
    $sSql .= "                       WHEN Substr(r70_estrut, 10, 2) = '04' THEN 'FUNDEB 40%'                             ";
    $sSql .= "                       ELSE o15_descr                                                                      ";
    $sSql .= "                     end                                                                                   ";
    $sSql .= "                 end  AS recurso,                                                                          ";
    $sSql .= "                 rh01_regist,                                                                              ";
    $sSql .= "                 z01_nome,                                                                                 ";
    $sSql .= "                 z01_cgccpf,                                                                               ";
    $sSql .= "                 rh01_admiss,                                                                              ";
    $sSql .= "                 rh01_nasc,                                                                                ";
    $sSql .= "                 z01_ender,                                                                                ";
    $sSql .= "                 z01_numero,                                                                               ";
    $sSql .= "                 z01_compl,                                                                                ";
    $sSql .= "                 z01_bairro,                                                                               ";
    $sSql .= "                 z01_munic,                                                                                ";
    $sSql .= "                 z01_cep,                                                                                  ";
    $sSql .= "                 z01_uf,                                                                                   ";
    $sSql .= "                 rh16_catres,                                                                              ";
    $sSql .= "                 rh16_ctps_n,                                                                              ";
    $sSql .= "                 rh16_ctps_s,                                                                              ";
    $sSql .= "                 rh16_ctps_d,                                                                              ";
    $sSql .= "                 rh16_ctps_uf,                                                                             ";
    $sSql .= "                 rh16_pis,                                                                                 ";
    $sSql .= "                 rh05_aviso,                                                                               ";
    $sSql .= "                 rh05_recis,                                                                               ";
    $sSql .= "                 rh05_mremun,                                                                              ";
    $sSql .= "                 h13_tpcont,                                                                               ";
    $sSql .= "                 h13_descr,                                                                                ";
    $sSql .= "                 r59_descr,                                                                                ";
    $sSql .= "                 o55_projativ,                                                                             ";
    $sSql .= "                 o55_descr,                                                                                ";
    $sSql .= "                 o15_codigo,                                                                               ";
    $sSql .= "                 o15_descr,                                                                                ";
    $sSql .= "                 o40_orgao,                                                                                ";
    $sSql .= "                 o40_descr,                                                                                ";
    $sSql .= "                 o41_unidade,                                                                              ";
    $sSql .= "                 o41_descr,                                                                                ";
    $sSql .= "                 rh30_regime,                                                                              ";
    $sSql .= "                 r59_movsef,                                                                               ";
    $sSql .= "                 r52_perc,                                                                                 ";
    $sSql .= "                 rh115_sigla,                                                                              ";
    $sSql .= "                 rh115_descricao,                                                                          ";
    $sSql .= "                 rh30_regime,                                                                              ";
    $sSql .= "                 rh02_seqpes,                                                                              ";
    $sSql .= "                 rh116_codigo,                                                                             ";
    $sSql .= "                 rh116_cnpj,                                                                               ";
    $sSql .= "                 rh116_descricao,                                                                          ";
    $sSql .= "                 rh52_descr,                                                                               ";
    $sSql .= "                 Substr(Db_fxxx(rh01_regist, rh02_anousu, rh02_mesusu, 1), 111, 11)::float8 AS f010,       ";
    $sSql .= "                 r59_codsaq                                                                                ";
    $sSql .= " FROM  gerfres                                                                                             ";
    $sSql .= "        INNER JOIN rhpessoalmov   ON rhpessoalmov.rh02_anousu  = gerfres.r20_anousu                        ";
    $sSql .= "                                 AND rhpessoalmov.rh02_mesusu  = gerfres.r20_mesusu                        ";
    $sSql .= "                                 AND rhpessoalmov.rh02_regist  = gerfres.r20_regist                        ";
    $sSql .= "                                 AND rhpessoalmov.rh02_instit  = {$iInstituicao}                           ";
    $sSql .= "        INNER JOIN rhpessoal ON rhpessoal.rh01_regist        = rhpessoalmov.rh02_regist                    ";
    $sSql .= "        LEFT  JOIN rhsindicato ON rhpessoal.rh01_rhsindicato = rhsindicato.rh116_sequencial                ";
    $sSql .= "        LEFT JOIN rhpesdoc ON rhpesdoc.rh16_regist           = rhpessoal.rh01_regist                       ";
    $sSql .= "        INNER JOIN cgm ON cgm.z01_numcgm                     = rhpessoal.rh01_numcgm                       ";
    $sSql .= "        INNER JOIN rhlota ON rhlota.r70_codigo               = rhpessoalmov.rh02_lota                      ";
    $sSql .= "                         AND rhlota.r70_instit               = rhpessoalmov.rh02_instit                    ";
    $sSql .= "        LEFT JOIN rhregime ON rhregime.rh30_codreg           = rhpessoalmov.rh02_codreg                    ";
    $sSql .= "                          AND rhregime.rh30_instit           = rhpessoalmov.rh02_instit                    ";
    $sSql .= "        LEFT JOIN rhcadregime ON rhcadregime.rh52_regime     = rhregime.rh30_regime                        ";
    $sSql .= "        LEFT JOIN rhlotaexe ON rhlotaexe.rh26_anousu         = rhpessoalmov.rh02_anousu                    ";
    $sSql .= "                           AND rhlotaexe.rh26_codigo         = rhlota.r70_codigo                           ";
    $sSql .= "        LEFT JOIN orcunidade ON orcunidade.o41_anousu        = rhlotaexe.rh26_anousu                       ";
    $sSql .= "                            AND orcunidade.o41_orgao         = rhlotaexe.rh26_orgao                        ";
    $sSql .= "                            AND orcunidade.o41_unidade       = rhlotaexe.rh26_unidade                      ";
    $sSql .= "        LEFT JOIN orcorgao ON orcorgao.o40_anousu            = orcunidade.o41_anousu                       ";
    $sSql .= "                          AND orcorgao.o40_orgao             = orcunidade.o41_orgao                        ";
    $sSql .= "        LEFT JOIN rhlotavinc ON rhlotavinc.rh25_codigo       = rhlotaexe.rh26_codigo                       ";
    $sSql .= "                            AND rhlotavinc.rh25_anousu       = rhpessoalmov.rh02_anousu                    ";
    $sSql .= "                            AND rhlotavinc.rh25_vinculo      = rhregime.rh30_vinculo                       ";
    $sSql .= "        LEFT JOIN orcprojativ ON orcprojativ.o55_anousu      = rhpessoalmov.rh02_anousu                    ";
    $sSql .= "                             AND orcprojativ.o55_projativ    = rhlotavinc.rh25_projativ                    ";
    $sSql .= "        LEFT JOIN orctiporec ON orctiporec.o15_codigo        = rhlotavinc.rh25_recurso                     ";
    $sSql .= "        LEFT JOIN rhpesrescisao ON rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes                    ";
    $sSql .= "        LEFT JOIN pensao ON pensao.r52_regist                = rhpessoalmov.rh02_regist                    ";
    $sSql .= "                        AND pensao.r52_anousu                = rhpessoalmov.rh02_anousu                    ";
    $sSql .= "                        AND pensao.r52_mesusu                = rhpessoalmov.rh02_mesusu                    ";
    $sSql .= "        LEFT JOIN rescisao ON rescisao.r59_anousu            = rhpessoalmov.rh02_anousu                    ";
    $sSql .= "                          AND rescisao.r59_mesusu            = rhpessoalmov.rh02_mesusu                    ";
    $sSql .= "                          AND rescisao.r59_regime            = rhregime.rh30_regime                        ";
    $sSql .= "                          AND rescisao.r59_causa             = rhpesrescisao.rh05_causa                    ";
    $sSql .= "                          AND rescisao.r59_caub              = rhpesrescisao.rh05_caub::char(2)            ";
    $sSql .= "                          AND rescisao.r59_instit            = rhpessoalmov.rh02_instit                    ";
    $sSql .= "    LEFT JOIN causaafastamento ON causaafastamento.rh115_sequencial = rescisao.r59_causaafastamento        ";
    $sSql .= "        INNER JOIN tpcontra ON tpcontra.h13_codigo = rhpessoalmov.rh02_tpcont                              ";
    $sSql .= " WHERE {$sWhere}                                                                                           ";
    $sSql .= " ORDER BY rh01_regist                                                                                      ";
  
    return $sSql;
  } 

}
?>